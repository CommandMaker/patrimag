@extends('templates.admin')

@section('title', 'Créer un article')

@section('body')
    <div class="container">
        <div class="article-edit-header is-flex is-flex-direction-row is-justify-content-space-between is-align-items-center">
            <h2>Créer un article</h2>

            <button form="edit-article-form" type="submit" class="button">Sauvegarder</button>
        </div>

        <form action="" id="edit-article-form" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="field">
                <label for="title" class="label">Titre :</label>

                <div class="control">
                    <input type="text" class="input" id="title" name="title" placeholder="Titre de l'article">
                </div>
                @error('title')
                    <p class="help-text is-danger">{{ $message }}</p>
                @enderror
            </div>

            <div class="field">
                <label for="description" class="label">Description :</label>

                <div class="textarea-control">
                    <textarea type="text" class="input" id="description" name="description" placeholder="Description de l'article"></textarea>
                </div>
                @error('description')
                    <p class="help-text is-danger">{{ $message }}</p>
                @enderror
            </div>

            <p class="label">Image de bannière de l'article :</p>
            <div class="field file-field is-flex is-flex-direction-column is-align-items-center is-justify-content-center" style="gap: 10px">
                <label for="image" class="file-label">
                    <i class="ri-upload-cloud-line"></i>
                    Choisir un fichier
                </label>
                <input type="file" name="image" id="image" accept="image/*" class="file">
            </div>
            @error('image')
                <p class="help-text is-danger">{{ $message }}</p>
            @enderror

            <div class="field">
                <label for="admin-content" class="label">Contenu :</label>

                <textarea name="content" id="admin-content"></textarea>
                @error('content')
                        <p class="help-text is-danger">{{ $message }}</p>
                @enderror
            </div>
        </form>
    </div>
@endsection