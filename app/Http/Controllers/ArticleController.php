<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Comment;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Parsedown;

class ArticleController extends Controller
{
    public function showAll(): View
    {
        /** @var Article[] $articles */
        $articles = Article::paginate(9);

        return view('pages.articles.show-all', [
            'articles' => $articles,
        ]);
    }

    public function showOne(int $id, string $slug, Parsedown $parsedown): View|RedirectResponse
    {
        $article = Article::findOrFail($id);

        if ($article->slug !== $slug) {
            return redirect()->route('article.show-one', ['slug' => $article->slug, 'id' => $id]);
        }

        $article->content = $parsedown->text($article->content);

        return view('pages.articles.show-one', [
            'article' => $article,
        ]);
    }

    public function submitComment(Request $request, int $id): RedirectResponse
    {
        if (!auth()->user()) {
            return back()->withErrors(['msg' => 'Je ne sais pas comment vous avez trouvé ça mais vous devez être connecté pour vous en servir !']);
        }

        if (!Article::find($id)) {
            return back()->with('error', 'L\'article spécifié n\'existe pas');
        }

        $request->validate([
            'parent' => 'integer|nullable',
            'comment_content' => 'string|required',
        ]);

        if ($request->parent && !Article::find($request->parent)) {
            return back()->with('error', 'La réponse est associée à un commentaire qui n\'existe pas');
        }

        Comment::create([
            'content' => nl2br($request->comment_content),
            'article_id' => $id,
            'author_id' => auth()->user()->id,
            'parent' => $request->parent ?? null,
        ]);

        return back()->with('success', 'Votre commentaire a bien été publié !');
    }

    public function deleteComment(Request $request): RedirectResponse
    {
        $cid = $request->cid;

        if (!$cid) {
            return back()->with('error', 'Il est nécéssaire de spécifier un commentaire pour le supprimer');
        }

        if (!auth()->user()) {
            return back()->with('error', 'Je ne sais pas comment vous avez trouvé ça mais vous devez être connecté pour vous en servir !');
        }

        $comment = Comment::find($cid);

        if (!$comment) {
            return back()->with('error', 'Le commentaire spécifié n\'existe pas !');
        }

        if ($comment->author_id !== auth()->user()->id) {
            return back()->with('error', 'Ce n\'est pas bien de vouloir supprimer les commentaires des autres !');
        }

        $comment->delete();

        return back()->with('success', 'Votre commentaire a bien été supprimé !');
    }
}
