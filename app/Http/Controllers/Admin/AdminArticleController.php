<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Article;
use Cocur\Slugify\Slugify;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Storage;

class AdminArticleController extends Controller
{
    public function list(Request $request): View
    {
        $articles = Article::paginate(20);

        if (strtolower($request->get('show')) === 'all') {
            $articles = Article::withTrashed()->paginate(20);
        }

        return view('pages.admin.articles.articles-list', [
            'articles' => $articles,
        ]);
    }

    public function createView(): View
    {
        return view('pages.admin.articles.article-create');
    }

    public function create(Request $request, Slugify $slugify): RedirectResponse
    {
        $request->validate([
            'title' => 'required|string|min:5|unique:articles,title',
            'content' => 'required|string|min:10',
            'description' => 'required|string|min:10',
            'image' => 'required|image',
        ], [
            'title.unique' => 'Un article avec ce titre existe déjà',
        ]);

        $imagePath = $request->file('image')->store('articles_image');

        Article::create([
            'title' => $request->title,
            'slug' => $slugify->slugify($request->title),
            'description' => $request->description,
            'image' => $imagePath,
            'content' => $request->input('content'),
            'author_id' => auth()->user()->id,
            'likes' => 0,
            'dislikes' => 0,
        ]);

        return redirect()->route('admin.article.show-all')->with('success', 'L\'article a bien été créé !');
    }

    public function editView(int $id): View|RedirectResponse
    {
        $article = Article::withTrashed()->find($id);

        if (!$article) {
            return back()->with('error', 'L\'article n\'existe pas !');
        }

        return view('pages.admin.articles.article-edit', [
            'article' => $article,
        ]);
    }

    public function edit(Request $request, int $id, Slugify $slugify): RedirectResponse
    {
        $article = Article::withTrashed()->find($id);

        if (!$article) {
            return back()->with('error', 'L\'article n\'existe pas !');
        }

        $request->validate([
            'title' => 'required|string|min:5',
            'content' => 'required|string|min:10',
            'description' => 'required|string|min:10',
            'image' => 'image',
        ], [
            'title.unique' => 'Un article avec ce titre existe déjà',
        ]);

        if ($request->image) {
            Storage::disk('public')->delete($article->image);
        }

        $imagePath = $request->image ? $request->file('image')->store('articles_image', 'public') : $article->image;

        $article->update([
            'title' => $request->title,
            'slug' => $slugify->slugify($request->title),
            'description' => $request->description,
            'content' => $request->input('content'),
            'image' => $imagePath,
        ]);

        return redirect()->route('admin.article.show-all')->with('success', 'L\'article a bien été édité !');
    }

    public function delete(int $id): RedirectResponse
    {
        $article = Article::find($id);

        if (!$article) {
            return back()->with('error', 'L\'article spécifié n\'existe pas !');
        }

        $article->delete();

        return back()->with('success', 'L\'article a bien été supprimé');
    }

    public function restore(int $id): RedirectResponse
    {
        $article = Article::withTrashed()->find($id);

        if (!$article) {
            return back()->with('error', 'L\'article spécifié n\'existe pas !');
        }

        $article->restore();

        return back()->with('success', 'L\'article a bien été restoré !');
    }
}
