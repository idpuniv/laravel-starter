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



<div class="row mb-3">
    {{-- Icône --}}
    <div class="col-md-6">
        <label class="form-label">Icône</label>
        <input type="text"
               name="icon"
               class="form-control @error('icon') is-invalid @enderror"
               value="{{ old('icon', $team->icon ?? '') }}">
        @error('icon')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    {{-- Statut --}}
    <div class="col-md-6">
        <label class="form-label">Statut</label>

        @php
            $currentStatus = old('status', $team->status ?? 'active');
        @endphp

        <div class="form-check form-switch" style="min-height: 38px; display: flex; align-items: center;">
            <input type="checkbox"
                   class="form-check-input"
                   name="status"
                   id="statusSwitch"
                   value="active"
                   @checked($currentStatus === 'active')
                   style="width: 3rem; height: 1.5rem; margin-top: 0;">
            <label class="form-check-label ms-2" for="statusSwitch"></label>
        </div>

        @error('status')
            <div class="text-danger small mt-1">{{ $message }}</div>
        @enderror
    </div>
</div>