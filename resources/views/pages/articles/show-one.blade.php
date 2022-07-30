@extends('templates.base')

@section('title', $article->title)

@section('body')
    <div class="landing-page">
        <img src="{{ $article->image }}" alt="Image de la banière de l'article" class="landing-image">
        <div class="landing-content">
            <h1 class="landing-title">{{ $article->title }}</h1>
            <p class="landing-description">Par <strong>{{ $article->author->name }}</strong> le {{ $article->created_at->format('d/m/Y') }} à {{ $article->created_at->format('H:i') }} | Dernière modification le {{ $article->updated_at->format('d/m/Y à H:i') }}</p>
        </div>
    </div>

    <article class="article">
        {!! html_entity_decode($article->content) !!}
    </article>

    <hr>

    <section class="comments-section">
        <h2 class="section-title">L'article vous a plu ? Laissez-un commentaire !</h2>

        @if (Session::get('success'))
            <div class="notification is-success">
                {{ Session::get('success') }}
            </div>
        @endif

        @if (Session::get('error'))
            <div class="notification is-danger">
                {{ Session::get('error') }}
            </div>
        @endif

        @error ('comment_content')
            <div class="notification is-danger">
                {{ $message }}
            </div>
        @enderror

        @guest
            <h3 class="auth-warn-comment">Vous devez être connecté pour poster un commentaire</h3>
        @endguest
        @auth
            <form action="{{ route('article.submit-comment', ['id' => $article->id]) }}" method="POST">
                @csrf
                <div class="field">
                    <label class="label" for="comment-md-editor">Votre commentaire</label>
                    <textarea name="comment_content" id="comment-md-editor"></textarea>
                </div>

                <div class="text-right">
                    <button type="submit" class="button text-right">Publier</button>
                </div>
            </form>
        @endauth

        <h2 class="section-title">Les autres commentaires</h2>

        <div class="comments">
            @foreach($article->comments as $comment)
                <div class="comment">
                    <div class="comment-header">Écrit par <b class="comment-author">{{ $comment->author->name }}</b> le {{ $comment->created_at->format('d/m/Y à H:i') }}</div>
                    <div class="comment-body">
                        {!! html_entity_decode(Parsedown::instance()->text($comment->content)) !!}
                    </div>
                </div>
            @endforeach
        </div>
    </section>
@endsection
