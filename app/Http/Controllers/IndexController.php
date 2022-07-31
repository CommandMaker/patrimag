<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Contracts\View\View;

class IndexController extends Controller
{
    public function index(): View
    {
        /** @var Article[] $articles */
        $articles = Article::limit(6)
            ->orderByDesc('created_at')
            ->get();

        return view('index', [
            'articles' => $articles,
        ]);
    }
}
