<?php

namespace App\Http\Controllers\Security;

use DateTime;
use Password;
use Carbon\Carbon;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Mail\Security\RegisterMail;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Auth\Events\PasswordReset;
use App\Mail\Security\PasswordUpdatedMail;
use App\Mail\Security\AccountVerificationSuccessfulMail;

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
            '_confirm-password' => 'required|max:255|min:8|same:_password',
            'g-recaptcha-response' => 'required|captcha'
        ], [
            '_username.unique' => 'Ce nom d\'utilisateur est déjà pris !',
            '_email.unique' => 'Cet adresse email est déjà prise !',
            '_confirm-password.same' => 'Vos mots de passes ne correspondent pas !'
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

        Mail::to($user)->send(new AccountVerificationSuccessfulMail($user));

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

        if (Auth::attempt(['email' => $credentials['_email'], 'password' => $credentials['_password']], remember: $request->remember_me)) {
            $request->session()->regenerate();

            return redirect()->route('index.index');
        }

        return back()->withErrors([
            'authentication-error' => 'Vos identifiants sont invalides',
        ])->withInput($request->only('_email'));
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
        $registeredSince = Carbon::parse(auth()->user()->created_at)->diffForHumans(null, true);

        return view('pages.security.profile', [
            'registeredSince' => $registeredSince
        ]);
    }

    public function editProfile (Request $request)
    {
        /** @var User $user */
        $user = auth()->user();

        if ($request->has('update')) {
            $credentials = $request->validate([
                'username' => 'required|max:255|min:8|unique:users,email,' . $user->id,
                'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            ], [
                'username.unique' => 'Ce nom d\'utilisateur est déjà pris !',
                'email.unique' => 'Cet adresse email est déjà prise !',
            ]);

            $user->update([
                'name' => $request->username,
                'email' => $request->email
            ]);
        }

        if ($request->has('password')) {
            $credentials = $request->validate([
                'actual_password' => 'required|max:255|min:8',
                'new_password' => 'required|max:255|min:8',
                'confirm_new_password' => 'required|max:255|min:8|same:new_password',
            ], [
                'confirm_new_password.same' => 'Vos mots de passes ne correspondent pas !'
            ]);

            if (!Hash::check($request->actual_password, $user->password)) {
                return back()->withErrors([
                    'actual_password' => 'Le mot de passe n\'est pas identique à votre mot de passe actuel'
                ]);
            }

            $user->update([
                'password' => Hash::make($request->new_password)
            ]);

            Mail::to($user)->send(new PasswordUpdatedMail($user));
        }

        return back();
    }

    public static function generateActivationToken (string $email): string
    {
        return sha1(mt_rand(10000, 99999) . time() . sha1($email));
    }
}
