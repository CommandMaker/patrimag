<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;
use Parsedown;

class ArticleController extends Controller
{

    public function showAll ()
    {
        /** @var Article[] $articles */
        $articles = Article::paginate(9);

        return view('pages.articles.show-all', [
            'articles' => $articles
        ]);
    }

    public function showOne (int $id, string $slug, Parsedown $parsedown)
    {
        $article = Article::findOrFail($id);

        if ($article->slug !== $slug) {
            return redirect()->route('article.show-one', ['slug' => $article->slug, 'id' => $id]);
        }

        $article->content = $parsedown->text($article->content);

        return view('pages.articles.show-one', [
            'article' => $article
        ]);
    }
}
