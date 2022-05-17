@extends('templates.base')

@section('title', 'Nos articles')

@section('body')
    <div class="container">
        <h1 class="title is-1">Nos articles</h1>

        {{ $articles->links() }}

        <div class="articles-table-view">
            @foreach ($articles as $article)
                <a href="{{ route('article.show-one', ['id' => $article->id, 'slug' => $article->slug]) }}" class="article-row">
                    <div class="article-content">
                        <p class="article-title">{{ $article->title }}</p>

                        <p class="article-description">{{ Str::limit($article->content, 700) }}</p>
                    </div>
                    <img src="{{ $article->image }}" alt="{{ $article->title }}" title="{{ $article->title }}" class="article-img">
                </a>
            @endforeach
        </div>

        {{ $articles->links() }}
    </div>
@endsection
