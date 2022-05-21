<h1>Valider votre compte</h1>

<p>Bonjour {{ $user->name }}</p>

<p>Vous avez récemment créé un compte sur notre site Patri-Mag avec l'addresse e-mail {{ $user->email }}</p>
<p>Pour valider votre compte, cliquez sur le lien <a href="{{ route('security.verify-account', ['u' => $user->id, 'token' => $user->verify_token]) }}">suivant</a></p>

<p>Cordialement <br>L'équipe des comptes Patri-Mag</p>
