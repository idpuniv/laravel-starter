<div class="row g-4">
    <!-- Ligne 1 -->
    <div class="col-md-6">
        <label for="title" class="form-label fw-semibold">{{ __('Titre de l\'article') }}</label>
        <input type="text" id="title" name="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title', $post->title ?? '') }}" placeholder="{{ __('Ex. Guide complet pour déposer un brevet') }}">
        @error('title')
        <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        <label for="category_id" class="form-label fw-semibold">{{ __('Catégorie') }}</label>
        <div class="input-group">
            <select id="category_id" name="category_id" class="form-select @error('category_id') is-invalid @enderror">
                <option value="">{{ __('Choisir une catégorie') }}</option>
                @foreach($categories as $cat)
                <option value="{{ $cat->id }}" {{ old('category_id', $post->category_id ?? '') == $cat->id ? 'selected' : '' }}>
                    {{ $cat->name }}
                </option>
                @endforeach
            </select>
            <button class="btn btn-outline-secondary" type="button" id="addCategoryBtn" data-bs-toggle="modal" data-bs-target="#catModalForm">
                <i class="bi bi-plus"></i>
            </button>
        </div>
        @error('category_id')
        <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <!-- Ligne 2 -->
    <div class="col-md-6">
        <label for="author" class="form-label fw-semibold">{{ __('Auteur') }}</label>
        <input type="text" id="author" class="form-control" value="{{ $post->user->name ?? auth()->user()->name ?? '' }}" disabled>
        <input type="hidden" name="user_id" value="{{ auth()->id() }}">
    </div>

    <div class="col-md-6">
        <label for="published_at" class="form-label fw-semibold">{{ __('Date de publication') }}</label>
        <input type="date" id="published_at" name="published_at" class="form-control @error('published_at') is-invalid @enderror" value="{{ old('published_at', isset($post) && $post->published_at ? $post->published_at->format('Y-m-d') : '') }}">
        <div class="form-text">{{ __('Indiquez une date pour planifier une publication ultérieure automatique à la date indiquée') }}</div>
        @error('published_at')
        <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <!-- Image à la une -->
    <div class="col-12">
        <label for="featured_image" class="form-label fw-semibold">{{ __('Image à la une') }}</label>
        <input type="file" id="featured_image" name="featured_image" class="form-control @error('featured_image') is-invalid @enderror" accept="image/*">
        <div class="form-text">{{ __('Formats acceptés : JPG, PNG. Taille recommandée : 1200×630 px.') }}</div>
        @error('featured_image')
        <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <!-- Galerie d'images -->
    <div class="col-12">
        <label for="gallery" class="form-label fw-semibold">{{ __('Galerie d\'images') }}</label>
        <input type="file" id="gallery" name="gallery[]" class="form-control @error('gallery') is-invalid @enderror" accept="image/*" multiple>
        <div class="form-text">{{ __('Vous pouvez sélectionner plusieurs images à afficher dans l\'article.') }}</div>
        @error('gallery')
        <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <!-- Résumé -->
    <div class="col-12">
        <label for="summary" class="form-label fw-semibold">{{ __('Résumé (aperçu)') }}</label>
        <textarea id="summary" name="summary" class="form-control @error('summary') is-invalid @enderror" rows="3" maxlength="160" placeholder="{{ __('Court extrait affiché sur les cartes d\'articles...') }}">{{ old('summary', $post->summary ?? '') }}</textarea>
        <div class="form-text">{{ __('Aperçu affiché dans la liste des actualités (160 caractères max).') }}</div>
        @error('summary')
        <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <!-- Contenu -->
    <div class="col-12">
        <label for="content" class="form-label fw-semibold">{{ __('Contenu') }}</label>
        <textarea id="content" name="content" class="form-control @error('content') is-invalid @enderror" rows="10" placeholder="{{ __('Rédigez le contenu de l\'article...') }}">{{ old('content', $post->content ?? '') }}</textarea>
        @error('content')
        <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>


<form method="POST" id="category-form">
    @csrf
    <x-modal id="catModalForm"
        title="{{ __('Créer une nouvelle catégorie') }}"
        class="modal-dialog-centered">

        <div class="mb-3">
            <div class="status status-success d-none" id="category-success">{{ __('Catégorie enregistrée')}}</div>
            @include('admin.categories.partials.form')
        </div>

        <div class="d-flex justify-content-end gap-2">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                {{ __('Annuler') }}
            </button>
            <button type="button" class="btn btn-primary" id="btn-save-category">
                {{ __('Enregistrer') }}
            </button>
        </div>
    </x-modal>
</form>