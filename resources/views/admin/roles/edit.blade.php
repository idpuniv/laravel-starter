@section('title', 'Modifier le rôle')

<x-admin-layout>
<div class="container py-4">

    <h3>Modifier le rôle</h3>

    <form action="{{ route('admin.roles.update', $role->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label class="form-label">Nom du rôle</label>
            <input type="text"
                   name="label"
                   class="form-control @error('label') is-invalid @enderror"
                   value="{{ old('label', $role->label) }}">

            @error('label')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label class="form-label">Permissions</label>

            <div class="row g-2">

                @foreach($permissions as $permission)
                    <div class="col-md-3">

                        <div class="form-check">
                            <input class="form-check-input"
                                   type="checkbox"
                                   name="permissions[]"
                                   value="{{ $permission->name }}"
                                   id="perm_{{ $permission->id }}"
                                   @checked($role->permissions->contains('name', $permission->name))>

                            <label class="form-check-label" for="perm_{{ $permission->id }}">
                                {{ $permission->label }}
                            </label>
                        </div>

                    </div>
                @endforeach

            </div>

            @error('permissions')
                <div class="text-danger small mt-1">{{ $message }}</div>
            @enderror
        </div>

        <div class="d-flex justify-content-between">

            <a href="{{ route('admin.roles.index') }}" class="btn btn-outline-secondary">
                Retour
            </a>

            <button class="btn btn-primary">
                Mettre à jour
            </button>

        </div>

    </form>

</div>
</x-admin-layout>