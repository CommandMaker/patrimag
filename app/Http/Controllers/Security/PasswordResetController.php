<?php


namespace App\Http\Controllers\Security;


use App\Mail\Security\PasswordUpdatedMail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;

class PasswordResetController
{

    public function resetPasswordLinkView ()
    {
        return view('pages.security.password-forgot');
    }

    public function resetPasswordLink (Request $request)
    {
        $request->validate([
            'email' => 'required|email'
        ]);

        $status = Password::sendResetLink($request->only('email'));

        return $status === Password::RESET_LINK_SENT
            ? back()->with(['status' => __($status)])
            : back()->withErrors(['email' => __($status)]);
    }

    public function resetPasswordFormView ()
    {
        return view('pages.security.reset-password-form');
    }

    public function resetPasswordForm (Request $request)
    {

        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8|same:confirm-password',
            'confirm-password' => 'required'
        ], [
            'password.same' => 'Vos mots de passes ne correspondent pas !'
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'confirm-password', 'token'),
            function (User $user, string $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ]);

                $user->save();

                Mail::to($user)->send(new PasswordUpdatedMail($user));
            }
        );

        return $status === Password::PASSWORD_RESET
            ? redirect()->route('security.login-view')->with('status', __($status))
            : back()->withErrors(['email' => [__($status)]]);
    }
}
