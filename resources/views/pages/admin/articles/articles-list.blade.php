@extends('templates.admin')

@section('title', 'Tous les articles')

@section('body')
    @parent

    <div class="container">
        <h1 style="margin-bottom: 3rem;">Liste des articles du site</h1>

        {{ $articles->links() }}

        <div class="text-right" style="margin-bottom: 0.7rem;">
            <a href="{{ route('admin.article.create-view') }}" class="button link-without-animation">Créer un article</a>
        </div>

        <div class="text-right">
            @if(strtolower(request()->get('show')) === 'all')
                <a href="{{ route('admin.article.show-all') }}">Ne pas afficher les articles supprimés</a>
            @else
                <a href="{{ route('admin.article.show-all', ['show' => 'all']) }}">Afficher les articles supprimés</a>
            @endif
        </div>

        <div style="overflow: auto;">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">Titre</th>
                        <th scope="col">Auteur</th>
                        <th scope="col">Date de création</th>
                        <th scope="col">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($articles as $article)
                        <tr>
                            <th scope="row" @if($article->deleted_at !== null)style="color: rgb(var(--danger-color))"@endif>{{ $article->id }}</th>
                            <td><a href="{{ route('article.show-one', ['id' => $article->id, 'slug' => $article->slug]) }}" style="color: var(--primary-title-color);text-decoration: none;">{{ \Illuminate\Support\Str::limit($article->title) }}</a></td>
                            <td>{{ $article->author->name }}</td>
                            <td>{{ $article->created_at->format('d/m/Y H:i') }}</td>
                            <td class="is-flex is-flex-direction-row" style="gap: 10px">
                                <a href="{{ route('admin.article.edit-view', ['id' => $article->id]) }}" style="font-size: 1.5rem;text-decoration: none;" class="link-without-animation"><i class="ri-edit-2-line" style="color: #007bff;"></i></a>
                                @if($article->deleted_at)
                                    <form action="{{ route('admin.article.restore', ['id' => $article->id]) }}" method="post">
                                        @csrf
                                        <button style="outline: none;background: none;border: none;cursor:pointer;font-size: 1.5rem;"><i class="ri-arrow-go-back-line" style="color: rgb(var(--danger-color))"></i></button>
                                    </form>
                                @else
                                    <form action="{{ route('admin.article.delete', ['id' => $article->id]) }}" method="post">
                                        @csrf
                                        @method('DELETE')
                                        <button style="outline: none;background: none;border: none;cursor:pointer;font-size: 1.5rem;"><i class="ri-delete-bin-line" style="color: rgb(var(--danger-color))"></i></button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{ $articles->links() }}

    </div>
@endsection
