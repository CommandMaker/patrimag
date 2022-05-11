<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ArticleController extends Controller
{
    
    public function showAll ()
    {
        return view('pages.articles.show-all');
    }
}
