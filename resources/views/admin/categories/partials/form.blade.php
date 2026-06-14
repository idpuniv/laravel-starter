<div class="row g-4">
    <div class="col-md-6">
        <label for="name" class="form-label fw-semibold">{{ __('Nom de la catégorie') }} <span class="text-danger">*</span></label>
        <input type="text" id="name" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $category->name ?? '') }}" placeholder="{{ __('Ex. Brèves') }}" required>
        @error('name')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    
    <div class="col-md-6">
        <label for="parent_id" class="form-label fw-semibold">{{ __('Catégorie parente') }}</label>
        <select id="parent_id" name="parent_id" class="form-select @error('parent_id') is-invalid @enderror">
            <option value="">{{ __('Aucune (catégorie principale)') }}</option>
            @foreach($categories as $cat)
                <option value="{{ $cat->id }}" {{ old('parent_id', $category->parent_id ?? '') == $cat->id ? 'selected' : '' }}>
                    {{ str_repeat('— ', $cat->depth ?? 0) }}{{ $cat->name }}
                </option>
            @endforeach
        </select>
        <div class="form-text">{{ __('Laissez vide pour une catégorie principale') }}</div>
        @error('parent_id')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    
    <div class="col-12">
        <label for="slug" class="form-label fw-semibold">{{ __('Slug') }}</label>
        <input type="text" id="slug" name="slug" class="form-control @error('slug') is-invalid @enderror" value="{{ old('slug', $category->slug ?? '') }}" placeholder="{{ __('generer-automatiquement') }}">
        <div class="form-text">{{ __('Laisser vide pour générer automatiquement à partir du nom') }}</div>
        @error('slug')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    
    <div class="col-12">
        <label for="description" class="form-label fw-semibold">{{ __('Description') }}</label>
        <textarea id="description" name="description" class="form-control @error('description') is-invalid @enderror" rows="4" placeholder="{{ __('Description de la catégorie...') }}">{{ old('description', $category->description ?? '') }}</textarea>
        @error('description')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>