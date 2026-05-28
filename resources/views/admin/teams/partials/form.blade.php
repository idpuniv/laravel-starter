<div class="mb-3">
    <label class="form-label">Nom (technique)</label>

    <input type="text"
           name="name"
           class="form-control @error('name') is-invalid @enderror"
           value="{{ old('name', $team->name ?? '') }}">

    @error('name')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label class="form-label">Libellé</label>

    <input type="text"
           name="label"
           class="form-control @error('label') is-invalid @enderror"
           value="{{ old('label', $team->label ?? '') }}">

    @error('label')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label class="form-label">Description</label>

    <textarea name="description"
              class="form-control @error('description') is-invalid @enderror"
              rows="3">{{ old('description', $team->description ?? '') }}</textarea>

    @error('description')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label class="form-label">Icône</label>

    <input type="text"
           name="icon"
           class="form-control @error('icon') is-invalid @enderror"
           value="{{ old('icon', $team->icon ?? '') }}">

    @error('icon')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label class="form-label">Statut</label>

    <select name="status"
            class="form-select @error('status') is-invalid @enderror">

        <option value="active"
            @selected(old('status', $team->status ?? '') === 'active')>
            Actif
        </option>

        <option value="inactive"
            @selected(old('status', $team->status ?? '') === 'inactive')>
            Inactif
        </option>

    </select>

    @error('status')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>