<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;

class IndexController extends Controller
{

    public function index ()
    {
        /** @var Article[] $articles */
        $articles = Article::limit(6)
            ->orderBy('created_at')
            ->get();

        return view('index', [
            'articles' => $articles
        ]);
    }
}
