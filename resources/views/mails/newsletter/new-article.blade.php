<h1>Bonjour {{ $user->name }}</h1>

<p>Un nouvel article vient d'être créé sur le <b>Patri-Mag</b>, en voici un petit extrait :</p>

{!! html_entity_decode(Str::limit($article->content, 150, ' [...]')) !!}

<br>
<br>
<hr>

<i>Vous recevez ce mail car vous êtes inscrits sur la liste de notre newsletter. Pour vous désabonner, connectez-vous sur votre profil.</i>