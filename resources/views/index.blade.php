@extends('templates.base')

@section('title', 'Accueil')

@section('body')
    <div class="notification mt-4 has-text-centered py-6">
        <div class="container">
            <h1 class="title is-1">Les articles à la une</h1>
        </div>
    </div>

    <div class="container">
        <div @if (count($articles)) class="home-grid-view-container" @endif>
            @forelse ($articles as $article)
            <a href="{{ route('article.show-one', ['id' => $article->id, 'slug' => $article->slug]) }}" class="card home-grid-view-item" role="article">
                <img src="{{ $article->image }}" alt="{{ $article->title }}" class="card-img">
                <div class="card-body">
                    <p class="card-title">{{ Str::limit($article->title, 50) }}</p>
                    <p class="card-description">{{ Str::limit($article->content, 68) }}</p>
                </div>
            </a>
            @empty
                <div class="is-flex is-flex-direction-column is-align-items-center my-5">
                    <h3 class="title is-3">Il n'y a aucun article</h3>
                    <p>Quel dommage ! Il n'y a aucun article à vous proposer ...</p>
                </div>
            @endforelse
        </div>
        
    </div>
@endsection
