@extends('templates.base')

@section('title', 'Réinitialiser votre mot de passe')

@section('body')
    <div class="container">
        <h1>Réinitialiser votre mot de passe</h1>

        <form action="{{ route('password.update') }}" method="POST">
            @csrf

            <div class="field">
                <label for="password" class="label">Nouveau mot de passe :</label>
                <div class="control @error('password') is-danger @enderror">
                    <i class="icon-left ri-key-2-line"></i>
                    <input type="password" id="password" name="password" required placeholder="Nouveau mot de passe" class="input">
                </div>
                @error('password')
                    <p class="help-text is-danger">{{ $message }}</p>
                @enderror
            </div>

            <div class="field">
                <label for="confirm-password" class="label">Confirmez votre nouveau mot de passe :</label>
                <div class="control @error('confirm-password') is-danger @enderror">
                    <i class="icon-left ri-key-2-line"></i>
                    <input type="password" id="confirm-password" name="confirm-password" required placeholder="Confirmez votre nouveau mot de passe" class="input">
                </div>
                @error('confirm-password')
                    <p class="help-text is-danger">{{ $message }}</p>
                @enderror
            </div>

            <input type="hidden" name="token" value="{{ request()->token }}">
            <input type="hidden" name="email" value="{{ request()->email }}">

            <button class="button" type="submit">Modifier votre mot de passe</button>
        </form>
    </div>
@endsection
