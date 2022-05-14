<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;

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
}
