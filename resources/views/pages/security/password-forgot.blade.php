@extends('templates.base')

@section('title', 'Mot de passe oublié ?')

@section('body')
    <div class="register-container">
        <div class="form-centered-card" style="max-width: 500px;">
            <h1 style="margin-bottom: .5rem;">Mot de passe oublié ?</h1>

            @if (session('status'))
                <div class="notification">
                    {{ session('status') }}
                </div>
            @endif

            <form action="{{ route('security.password-reset') }}" method="POST">
                @csrf

                <div class="field">
                    <label for="email" class="label">Saisissez l'adresse e-mail de votre compte</label>

                    <div class="control">
                        <i class="icon-left ri-mail-line"></i>
                        <input type="email" id="email" name="email" class="input" placeholder="Adresse E-mail">
                    </div>
                    @error('email')
                        <p class="help-text is-danger">{{ $message }}</p>
                    @enderror
                </div>

                <div class="field">
                    {!! NoCaptcha::display() !!}

                    @error('g-recaptcha-response')
                    <p class="help-text is-danger">
                        {{ $message }}
                    </p>
                    @enderror
                </div>

                <button type="submit" class="button full-width">Se connecter</button>
            </form>
        </div>
    </div>
@endsection
