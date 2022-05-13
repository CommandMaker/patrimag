@extends('templates.base')

@section('title', 'Nos articles')

@section('body')
    <div class="container">
        <h1 class="title is-1">Nos articles</h1>

        @foreach ($articles as $article)
            <p>{{ $article->title }}</p>
            <br>
        @endforeach

        {{ $articles->links() }}
    </div>
@endsection
