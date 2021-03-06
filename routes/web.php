<?php

use App\Http\Controllers\ArticleController;
use App\Http\Controllers\IndexController;
use App\Http\Controllers\Security\PasswordResetController;
use App\Http\Controllers\Security\SecurityController;
use App\Http\Middleware\NotAuthenticated;
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

/* Articles view */
Route::get('/articles', [ArticleController::class, 'showAll'])->name('article.show-all');
Route::get('/article/{id}/{slug}', [ArticleController::class, 'showOne'])->whereNumber('id')->name('article.show-one');

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
