<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\IndexController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\Api\APICommentController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Admin\AdminIndexController;
use App\Http\Controllers\Security\SecurityController;
use App\Http\Controllers\Admin\AdminArticleController;
use App\Http\Controllers\Security\PasswordResetController;

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

Route::get('/contact', [ContactController::class, 'view'])->name('contact.view');

/* Articles view */
Route::get('/articles', [ArticleController::class, 'showAll'])->name('article.show-all');
Route::get('/article/{id}/{slug}', [ArticleController::class, 'showOne'])->whereNumber('id')->name('article.show-one');
Route::post('/article/{id}/submit-comment', [ArticleController::class, 'submitComment'])->whereNumber('id')->name('article.submit-comment');
Route::get('/article/delete-comment', [ArticleController::class, 'deleteComment'])->name('article.delete-comment');

/* Security */
Route::middleware('guest')->group(function () {
    Route::get('/inscription', [SecurityController::class, 'registerView'])->name('security.register-view');
    Route::post('/inscription', [SecurityController::class, 'register'])->name('security.register');

    Route::get('/connexion', [SecurityController::class, 'loginView'])->name('security.login-view');
    Route::post('/connexion', [SecurityController::class, 'login'])->name('security.login');
});

Route::get('/mot-de-passe-perdu', [PasswordResetController::class, 'resetPasswordLinkView'])->middleware('guest')->name('security.password-reset-view');
Route::post('/reset-password', [PasswordResetController::class, 'resetPasswordLink'])->middleware('guest')->name('security.password-reset');
Route::get('/mot-de-passe-perdu/{token}', [PasswordResetController::class, 'resetPasswordFormView'])->middleware('guest')->name('password.reset');
Route::post('/update-password', [PasswordResetController::class, 'resetPasswordForm'])->middleware('guest')->name('password.update');

Route::get('/verify-account', [SecurityController::class, 'verifyAccount'])->name('security.verify-account');
Route::get('/logout', [SecurityController::class, 'logout'])->name('security.logout');

Route::get('/profil', [SecurityController::class, 'profilePage'])->middleware('auth')->name('security.profile');
Route::post('/profil', [SecurityController::class, 'editProfile'])->middleware('auth')->name('security.edit-profile');

/* Administration */
Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    Route::get('/', [AdminIndexController::class, 'index'])->name('admin.index');

    /* Article management */
    Route::get('/articles', [AdminArticleController::class, 'list'])->name('admin.article.show-all');
    Route::get('/article/create', [AdminArticleController::class, 'createView'])->name('admin.article.create-view');
    Route::post('/article/create', [AdminArticleController::class, 'create'])->name('admin.article.create');
    Route::get('/article/{id}/edit', [AdminArticleController::class, 'editView'])->whereNumber('id')->name('admin.article.edit-view');
    Route::post('/article/{id}/edit', [AdminArticleController::class, 'edit'])->whereNumber('id')->name('admin.article.edit');

    Route::delete('/article/{id}/delete', [AdminArticleController::class, 'delete'])->whereNumber('id')->name('admin.article.delete');
    Route::post('/article/{id}/restore', [AdminArticleController::class, 'restore'])->whereNumber('id')->name('admin.article.restore');

    /* User Management */
    Route::get('/users', [AdminUserController::class, 'list'])->name('admin.user.show-all');
    Route::post('/user/{id}/ban', [AdminUserController::class, 'ban'])->whereNumber('id')->name('admin.user.ban');
    Route::post('/user/{id}/unban', [AdminUserController::class, 'unban'])->whereNumber('id')->name('admin.user.unban');
    Route::post('/user/{id}/suspend', [AdminUserController::class, 'suspend'])->whereNumber('id')->name('admin.user.suspend');
    Route::post('/user/{id}/unsuspend', [AdminUserController::class, 'unsuspend'])->whereNumber('id')->name('admin.user.unsuspend');
});

/* Article API */
Route::get('/api/comments/{id}', [APICommentController::class, 'getComments'])->whereNumber('id')->name('api.comments.get');
Route::post('/api/comments/new/{id}', [APICommentController::class, 'addComment'])->whereNumber('id')->name('api.comments.new');
Route::delete('/api/comments/delete/{id}', [APICommentController::class, 'deleteComment'])->whereNumber('id')->name('api.comments.delete');