@section('title', 'Modifier la permission')

<x-admin-layout>
<div class="container py-4">

    <h3>Modifier la permission</h3>

    <div class="alert alert-info">
        Le code <strong>{{ $permission->name }}</strong> n’est pas modifiable.
    </div>

    <form action="{{ route('admin.permissions.update', $permission->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label class="form-label">Label</label>
            <input type="text"
                   name="label"
                   class="form-control @error('label') is-invalid @enderror"
                   value="{{ old('label', $permission->label) }}">

            @error('label')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label class="form-label">Module</label>

            <select name="module_id"
                    class="form-select @error('module_id') is-invalid @enderror">

                <option value="">-- Aucun --</option>

                @foreach($modules as $module)
                    <option value="{{ $module->id }}"
                        @selected(old('module_id', $permission->module_id) == $module->id)>
                        {{ $module->label ?? $module->name }}
                    </option>
                @endforeach

            </select>

            @error('module_id')
                <div class="text-danger small mt-1">{{ $message }}</div>
            @enderror
        </div>

        <div class="d-flex justify-content-between">

            <a href="{{ route('admin.permissions.index') }}" class="btn btn-outline-secondary">
                Retour
            </a>

            <button class="btn btn-primary">
                Mettre à jour
            </button>

        </div>

    </form>

</div>
</x-admin-layout>