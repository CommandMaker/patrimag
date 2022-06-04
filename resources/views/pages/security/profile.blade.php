@extends('templates.base')

@section('title', 'Mon profil')

@section('body')
    <div class="profile-topbar">
        <div class="container">
            <h1 class="user-name">{{ auth()->user()->name }}</h1>
            <p class="user-desc">Inscrit depuis {{ $registeredSince }} @if (auth()->user()->is_admin)| (nb articles) article(s) écrit(s) @endif</p>
        </div>
    </div>

    <div class="container">
        <h1>Éditer votre compte</h1>

        <form action="{{ route('security.edit-profile') }}" method="POST">
            @csrf
            
            <div class="bordered-card">
                <p class="card-title">Identifiants</p>

                <div class="is-flex" style="gap: 20px">
                    <div class="field">
                        <label for="username" class="label">Nom d'utilisateur :</label>
                        <div class="control">
                            <i class="icon-left ri-user-line"></i>
                            <input type="text" id="username" name="username" value="{{ auth()->user()->name }}" placeholder="Votre nouveau nom d\'utilisateur" class="input @error('username') is-danger @enderror">
                        </div>
                        @error('username')
                            <p class="help-text is-danger">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="field">
                        <label for="email" class="label">Adresse E-mail :</label>
                        <div class="control">
                            <i class="icon-left ri-mail-line"></i>
                            <input type="text" id="email" name="email" value="{{ auth()->user()->email }}" placeholder="Votre nouvelle adresse email" class="input @error('email') is-danger @enderror">
                        </div>
                        @error('email')
                            <p class="help-text is-danger">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                
                <div class="text-right">
                    <button type="submit" class="button" name="update">Sauvegarder</button>
                </div>
            </div>
        </form>

        <form action="{{ route('security.edit-profile') }}" method="POST">
            @csrf
            
            <div class="bordered-card">
                <p class="card-title">Mot de passe</p>

                <div class="is-flex" style="gap: 20px">
                    <div class="field">
                        <label for="actual-password" class="label">Mot de passe actuel :</label>
                        <div class="control">
                            <i class="icon-left ri-key-2-line"></i>
                            <input type="password" id="actual-password" name="actual_password" placeholder="Votre mot de passe actuel" class="input @error('username') is-danger @enderror">
                        </div>
                        @error('actual_password')
                            <p class="help-text is-danger">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="field">
                        <label for="new-password" class="label">Nouveau mot de passe</label>
                        <div class="control">
                            <i class="icon-left ri-key-2-line"></i>
                            <input type="password" id="new-password" name="new_password" placeholder="Votre nouveau mot de passe" class="input @error('email') is-danger @enderror">
                        </div>
                        @error('new_password')
                            <p class="help-text is-danger">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="field">
                        <label for="confirm-new-password" class="label">Confirmer le mot de passe</label>
                        <div class="control">
                            <i class="icon-left ri-key-2-line"></i>
                            <input type="password" id="confirm-new-password" name="confirm_new_password" placeholder="Confirmez votre nouveau mot de passe" class="input @error('email') is-danger @enderror">
                        </div>
                        @error('confirm_new_password')
                            <p class="help-text is-danger">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="text-right">
                    <button type="submit" class="button" name="password">Changer mon mot de passe</button>
                </div>
            </div>
        </form>
    </div>
@endsection