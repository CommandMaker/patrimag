@extends('templates.base')

@section('title', $article->title)

@section('body')
    <div class="landing-page">
        <img src="{{ Storage::url($article->image) }}" alt="Image de la banière de l'article" class="landing-image">
        <div class="landing-content">
            <h1 class="landing-title">{{ $article->title }}</h1>
            <p class="landing-description">Par <strong>{{ $article->author->name }}</strong> le {{ $article->created_at->format('d/m/Y') }} à {{ $article->created_at->format('H:i') }} | Dernière modification le {{ $article->updated_at->format('d/m/Y à H:i') }}</p>
        </div>
    </div>

    <article class="article" data-id="{{ $article->id }}">
        {!! nl2br(html_entity_decode($article->content)) !!}
    </article>

    <hr>

    <section class="comments-section"></section>

    <script>
        let user;

        @if (auth()->user())
            user = {!! auth()->user()->toJson() !!};
        @endif
    </script>

@endsection
