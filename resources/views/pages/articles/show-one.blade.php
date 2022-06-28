@extends('templates.base')

@section('title', $article->title)

@section('body')
    <div class="landing-page">
        <img src="{{ $article->image }}" alt="Image de la banière de l'article" class="landing-image">
        <div class="landing-content">
            <h1 class="landing-title">{{ $article->title }}</h1>
            <p class="landing-description">Par <strong>{{ $article->author->name }}</strong> le {{ $article->created_at->format('d/m/Y') }} à {{ $article->created_at->format('H:i:s') }} | Dernière modification le {{ $article->updated_at->format('d/m/Y') }} à {{ $article->updated_at->format('H:i:s') }}</p>
        </div>
    </div>

    <article class="article">
        {!! html_entity_decode($article->content) !!}
    </article>

    <hr>

    <section class="comments-section">
        <h2 class="section-title">L'article vous a plu ? Laissez-un commentaire !</h2>
    </section>
@endsection