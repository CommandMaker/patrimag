@extends('templates.admin')

@section('title', 'Bienvenue sur l\'administration')

@section('body')
    <div class="container">
        <h1 style="margin-bottom: 3rem;">Bienvenue sur l'administration, {{ auth()->user()->name }}</h1>

        <div class="admin-links-grid">
            <a href="{{ route('admin.article.show-all') }}" class="bordered-card">
                <p class="card-icon"><i class="ri-newspaper-line"></i></p>
                <p class="card-title">Gérer les articles</p>
            </a>
            <a href="{{ route('admin.users.show-all') }}" class="bordered-card">
                <p class="card-icon"><i class="ri-user-line"></i></p>
                <p class="card-title">Gérer les utilisateurs</p>
            </a>
            <a href="#" class="bordered-card">
                <p class="card-icon"><i class="ri-question-answer-line"></i></p>
                <p class="card-title">Gérer les commentaires</p>
            </a>
        </div>
    </div>
@endsection
