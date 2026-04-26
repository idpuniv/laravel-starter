
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
            <input type="text" name="label" class="form-control"
                   value="{{ old('label', $permission->label) }}">
        </div>

        <div class="mb-3">
            <label class="form-label">Module</label>
            <select name="module_id" class="form-select">
                <option value="">-- Aucun --</option>
                @foreach($modules as $module)
                    <option value="{{ $module->id }}"
                        {{ $permission->module_id == $module->id ? 'selected' : '' }}>
                        {{ $module->label ?? $module->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <button class="btn btn-primary">Mettre à jour</button>
        <a href="{{ route('admin.permissions.index') }}" class="btn btn-secondary">Retour</a>
    </form>

</div>
</x-admin-layout>