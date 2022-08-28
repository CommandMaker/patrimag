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

    <section class="comments-section">
        <h2 class="section-title">L'article vous a plu ? Laissez-un commentaire !</h2>

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

        <div class="is-flex is-align-items-center is-justify-content-space-between">
            <h2 class="section-title" id="other-comments">Les autres commentaires</h2>
            <div>
                <label for="orderby">Trier par :</label>
                <select id="orderby">
                    <option value="desc">Les + récents</option>
                    <option value="asc">Les + anciens</option>
                </select>
            </div>
        </div>

        <div class="comments" id="comments-container"></div>
    </section>

        <script>
            let user;

            @if (auth()->user())
                user = {!! auth()->user()->toJson() !!};
            @endif
            
            const route = "{!! route('article.delete-comment') !!}";
        </script>

@endsection
