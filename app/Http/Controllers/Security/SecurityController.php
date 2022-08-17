<?php

namespace App\Http\Controllers\Security;

use App\Http\Controllers\Controller;
use App\Mail\Security\AccountVerificationSuccessfulMail;
use App\Mail\Security\PasswordUpdatedMail;
use App\Mail\Security\RegisterMail;
use App\Models\User;
use Carbon\Carbon;
use DateTime;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class SecurityController extends Controller
{
    public function registerView(): View
    {
        return view('pages.security.register');
    }

    public function register(Request $request): RedirectResponse
    {
        $request->validate([
            '_email' => 'required|string|max:255|min:8',
            '_username' => 'required|string|max:255|min:8',
        ]);

        if (User::withTrashed()->where('email', '=', $request->_email)->first() && User::withTrashed()->where('email', '=', $request->_email)->first()->trashed()) {
            return back()->with('error', 'Cet adresse mail a été bannie. Veuillez en choisir une autre');
        }

        if (User::withTrashed()->where('name', '=', $request->_username)->first() && User::withTrashed()->where('name', '=', $request->_username)->first()->trashed()) {
            return back()->with('error', 'Ce nom d\'utilisateur a été banni. Veuillez en choisir un autre');
        }

        if (User::where('email', '=', $request->_email)->first() && User::where('email', '=', $request->_email)->first()->is_suspended) {
            return back()->with('error', 'Cet adresse mail a été suspendu. Veuillez en choisir une autre');
        }

        if (User::where('name', '=', $request->_username)->first() && User::where('name', '=', $request->_username)->first()->is_suspended) {
            return back()->with('error', 'Ce nom d\'utilisateur a été suspendu. Veuillez en choisir un autre');
        }

        $credentials = $request->validate([
            '_username' => 'required|string|max:255|min:8|unique:users,name',
            '_email' => 'required|string|email|max:255|unique:users,email',
            '_password' => 'required|string|max:255|min:8',
            '_confirm-password' => 'required|string|max:255|min:8|same:_password',
            'g-recaptcha-response' => 'required|captcha',
        ], [
            '_username.unique' => 'Ce nom d\'utilisateur est déjà pris !',
            '_email.unique' => 'Cet adresse email est déjà prise !',
            '_confirm-password.same' => 'Vos mots de passes ne correspondent pas !',
        ]);

        $user = User::create([
            'name' => $request->_username,
            'email' => $request->_email,
            'password' => Hash::make($request->_password),
            'verify_token' => static::generateActivationToken($request->_email),
        ]);

        Mail::to($user)->send(new RegisterMail($user));

        return redirect()->route('security.login-view');
    }

    public function verifyAccount(Request $request): RedirectResponse
    {
        $u = $request->u;
        $token = $request->token;
        /** @var User|null $user */
        $user = User::find($u);

        if (!$u || !$token || !$user || $user->verify_token !== $token) {
            return redirect()->route('security.login-view')->withErrors(['_email' => 'Le token ou l\'utilisateur n\'est pas valide !']);
        }

        $user->update([
            'verify_token' => null,
            'email_verified_at' => new DateTime(),
        ]);

        Mail::to($user)->send(new AccountVerificationSuccessfulMail($user));

        return redirect()->route('security.login-view');
    }

    public function loginView(): View
    {
        return view('pages.security.login');
    }

    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            '_email' => 'email|max:255|required',
            '_password' => 'string|max:255|required',
        ]);

        if (Auth::attempt(['email' => $credentials['_email'], 'password' => $credentials['_password']], remember: $request->remember_me)) {
            $request->session()->regenerate();

            return redirect()->route('index.index');
        }

        return back()->with([
            'error' => 'Vos identifiants sont invalides',
        ])->withInput($request->only('_email'));
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return back();
    }

    public function profilePage(): View
    {
        $registeredSince = Carbon::parse(auth()->user()->created_at)->diffForHumans(null, 1);

        return view('pages.security.profile', [
            'registeredSince' => $registeredSince,
        ]);
    }

    public function editProfile(Request $request): RedirectResponse
    {
        /** @var User $user */
        $user = auth()->user();

        if ($request->has('update')) {
            $credentials = $request->validate([
                'username' => 'required|max:255|min:8|unique:users,name,' . $user->id,
                'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            ], [
                'username.unique' => 'Ce nom d\'utilisateur est déjà pris !',
                'email.unique' => 'Cet adresse email est déjà prise !',
            ]);

            $user->update([
                'name' => $request->username,
                'email' => $request->email,
            ]);
        }

        if ($request->has('password')) {
            $credentials = $request->validate([
                'actual_password' => 'required|max:255|min:8',
                'new_password' => 'required|max:255|min:8',
                'confirm_new_password' => 'required|max:255|min:8|same:new_password',
            ], [
                'confirm_new_password.same' => 'Vos mots de passes ne correspondent pas !',
            ]);

            if (!Hash::check($request->actual_password, $user->password)) {
                return back()->withErrors([
                    'actual_password' => 'Le mot de passe n\'est pas identique à votre mot de passe actuel',
                ]);
            }

            $user->update([
                'password' => Hash::make($request->new_password),
            ]);

            Mail::to($user)->send(new PasswordUpdatedMail($user));
            session()->put('success', 'Votre mot de passe a bien été modifié');
        }

        return back();
    }

    public static function generateActivationToken(string $email): string
    {
        return sha1(mt_rand(10000, 99999) . time() . sha1($email));
    }
}
