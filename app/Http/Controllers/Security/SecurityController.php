<?php

namespace App\Http\Controllers\Security;

use App\Mail\Security\AccountVerificationSuccessful;
use App\Mail\Security\RegisterMail;
use App\Models\User;
use DateTime;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class SecurityController extends Controller
{

    public function registerView ()
    {
        return view('pages.security.register');
    }

    public function register (Request $request)
    {
        $credentials = $request->validate([
            '_username' => 'required|max:255|min:8|unique:users,name',
            '_email' => 'required|email|max:255|unique:users,email',
            '_password' => 'required|max:255|min:8',
            '_confirm-password' => 'required|max:255|min:8|same:_password'
        ]);

        $user = User::create([
            'name' => $request->_username,
            'email' => $request->_email,
            'password' => Hash::make($request->_password),
            'verify_token' => static::generateActivationToken($request->_email)
        ]);

        Mail::to($user)->send(new RegisterMail($user));

        return redirect()->route('security.login-view');
    }

    public function verifyAccount (Request $request)
    {
        $u = $request->u;
        $token = $request->token;
        /** @var User $user */
        $user = User::find($u);

        if (!$u || !$token || !$user || $user->verify_token !== $token) {
            return redirect()->route('security.login-view')->withErrors(['_email' => 'Le token ou l\'utilisateur n\'est pas valide !']);
        }

        $user->update([
            'verify_token' => null,
            'email_verified_at' => new DateTime()
        ]);

        Mail::to($user)->send(new AccountVerificationSuccessful($user));

        return redirect()->route('security.login-view');
    }

    public function loginView ()
    {
        return view('pages.security.login');
    }

    public function login (Request $request)
    {
        $credentials = $request->validate([
            '_email' => 'email|max:255|required',
            '_password' => 'string|max:255|required'
        ]);

        if (Auth::attempt(['email' => $credentials['_email'], 'password' => $credentials['_password']])) {
            $request->session()->regenerate();

            return redirect()->route('index.index');
        }

        return back()->withErrors([
            '_email' => 'Vos identifiants sont invalides',
        ])->onlyInput('_email');
    }

    public function logout (Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return back();
    }

    public function profilePage ()
    {

    }

    public static function generateActivationToken (string $email): string
    {
        return sha1(mt_rand(10000,99999).time().sha1($email));
    }
}
