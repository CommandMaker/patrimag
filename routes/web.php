<?php

use App\Http\Controllers\ArticleController;
use App\Http\Controllers\IndexController;
use App\Http\Controllers\LocaleSwitcherController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [IndexController::class, 'index'])->name('index.index');

Route::get('/articles', [ArticleController::class, 'showAll'])->name('article.show-all');
Route::get('/article/{id}/{slug}', [ArticleController::class, 'showOne'])->whereNumber('id')->name('article.show-one');