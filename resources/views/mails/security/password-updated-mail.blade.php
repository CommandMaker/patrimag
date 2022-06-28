<p>Bonjour {{ $user->name }}</p>

<p>Votre mot de passe Patri-Mag a été modifié. Si vous n'êtes pas a l'origine de cette modification, vous pouvez le <a href="{{ route('security.password-reset-view') }}">réinitialiser</a>.</p>

<p>Cordialement <br>L'équipe des comptes Patri-Mag</p>
