@extends('templates.admin')

@section('title', 'Liste des utilisateurs')

@section('body')
    @parent

    <div class="container">
        <h1>Liste des utilisateurs du site</h1>

        {{ $users->links() }}

        <div class="text-right">
            @if(strtolower(request()->get('show')) === 'all')
                <a href="{{ route('admin.user.show-all') }}">Ne pas afficher les utilisateurs bannis</a>
            @else
                <a href="{{ route('admin.user.show-all', ['show' => 'all']) }}">Afficher les utilisateurs bannis</a>
            @endif
        </div>

        <div style="overflow: auto;">
            <table class="table table-striped">
                <thead>
                <tr>
                    <th scope="col">ID</th>
                    <th scope="col">E-mail</th>
                    <th scope="col">Nom d'utilisateur</th>
                    <th scope="col">Inscrit le</th>
                    <th scope="col">Actions</th>
                </tr>
                </thead>
                <tbody>
                @foreach($users as $user)
                    <tr>
                        <th scope="row" @if($user->deleted_at !== null)style="color: rgb(var(--danger-color))"@endif>{{ $user->id }}</th>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->created_at->format('d/m/Y H:i') }}</td>
                        <td class="is-flex is-flex-direction-row" style="gap: 10px">
                            @if($user->is_suspended)
                                <form action="{{ route('admin.user.unsuspend', ['id' => $user->id]) }}" method="post">
                                    @csrf
                                    <button style="outline: none;background: none;border: none;cursor:pointer;font-size: 1.5rem;" title="Réactiver l'utilisateur"><i class="ri-user-follow-line" style="color: rgb(var(--danger-color))"></i></button>
                                </form>
                            @else
                                <form action="{{ route('admin.user.suspend', ['id' => $user->id]) }}" method="post">
                                    @csrf
                                    <button style="outline: none;background: none;border: none;cursor:pointer;font-size: 1.5rem;" title="Suspendre l'utilisateur"><i class="ri-user-unfollow-line" style="color: rgb(var(--danger-color))"></i></button>
                                </form>
                            @endif
                            @if($user->deleted_at)
                                <form action="{{ route('admin.user.unban', ['id' => $user->id]) }}" method="post">
                                    @csrf
                                    <button style="outline: none;background: none;border: none;cursor:pointer;font-size: 1.5rem;" title="Débannir l'utilisateur"><i class="ri-arrow-go-back-line" style="color: rgb(var(--danger-color))"></i></button>
                                </form>
                            @else
                                <form action="{{ route('admin.user.ban', ['id' => $user->id]) }}" method="post">
                                    @csrf
                                    <button style="outline: none;background: none;border: none;cursor:pointer;font-size: 1.5rem;" title="Bannir l'utilisateur"><i class="ri-indeterminate-circle-line" style="color: rgb(var(--danger-color))"></i></button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>

        {{ $users->links() }}
    </div>
@endsection
