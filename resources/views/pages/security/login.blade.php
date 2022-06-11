@extends('templates.base')

@section('title', 'Se connecter')

@section('body')
    <div class="register-container">
        <div class="form-centered-card">
            <h1 style="margin-bottom: .5rem;">Se connecter</h1>

            @error('authentication-error')
                <div class="notification is-danger">
                    {{ $message }}
                </div>
            @enderror

            <form action="{{ route('security.login') }}" method="POST">
                @csrf

                <div class="field">
                    <label for="email" class="label">E-mail :</label>

                    <div class="control">
                        <i class="icon-left ri-mail-line"></i>
                        <input type="email" class="input" name="_email" id="email" value="{{ old('_email') }}" required placeholder="Adresse email">
                    </div>
                    @error('_email')
                    <p class="help-text is-danger">{{ $message }}</p>
                    @enderror
                </div>

                <div class="field">
                    <label for="password" class="label">Mot de passe :</label>

                    <div class="control">
                        <i class="icon-left ri-key-2-line"></i>
                        <input type="password" class="input" name="_password" id="password" required placeholder="Mot de passe">
                    </div>
                    @error('_password')
                    <p class="help-text is-danger">{{ $message }}</p>
                    @enderror
                </div>

                <div class="text-right" style="margin-bottom: 1.5rem;">
                    <a href="{{ route('security.password-reset-view') }}" class="text-right">Mot de passe oublié ?</a>
                </div>

                <button type="submit" class="button full-width">Se connecter</button>
            </form>
        </div>
    </div>
@endsection
