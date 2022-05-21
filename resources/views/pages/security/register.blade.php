@extends('templates.base')

@section('title', 'Inscription')

@section('body')
    <div class="register-container">
        <div class="form-centered-card">
            <h1 style="margin-bottom: .5rem;">S'inscrire</h1>

            <form action="{{ route('security.register') }}" method="POST">
                @csrf

                <div class="field">
                    <label for="username" class="label">Nom d'utilisateur :</label>
                    <div class="control">
                        <i class="icon-left ri-user-line"></i>
                        <input type="text" class="input" name="_username" id="username" required placeholder="Votre futur nom d'utilisateur">
                    </div>
                    @error('_username')
                        <p class="help-text is-danger">{{ $message }}</p>
                    @enderror
                </div>

                <div class="field">
                    <label for="email" class="label">E-mail :</label>

                    <div class="control">
                        <i class="icon-left ri-mail-line"></i>
                        <input type="email" class="input" name="_email" id="email" required placeholder="Votre adresse email">
                    </div>
                    @error('_email')
                        <p class="help-text is-danger">{{ $message }}</p>
                    @enderror
                </div>

                <div class="field">
                    <label for="password" class="label">Mot de passe :</label>

                    <div class="control">
                        <i class="icon-left ri-key-2-line"></i>
                        <input type="password" class="input" name="_password" id="password" required placeholder="Votre mot de passe">
                    </div>
                    @error('_password')
                        <p class="help-text is-danger">{{ $message }}</p>
                    @enderror
                </div>

                <div class="field">
                    <label for="confirm-password" class="label">Confirmez votre mot de passe :</label>

                    <div class="control">
                        <i class="icon-left ri-key-2-line"></i>
                        <input type="password" class="input" name="_confirm-password" id="confirm-password" required placeholder="Confirmez votre mot de passe">
                    </div>
                    @error('_confirm-password')
                        <p class="help-text is-danger">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit" class="button full-width">S'inscrire</button>
            </form>
        </div>
    </div>
@endsection
