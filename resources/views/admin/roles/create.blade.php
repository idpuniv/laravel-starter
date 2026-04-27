@section('title', 'Créer un rôle')

<x-admin-layout>
<div class="container py-4">

    <h3>Créer un rôle</h3>

    <form action="{{ route('admin.roles.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label class="form-label">Nom du rôle</label>
            <input type="text"
                   name="name"
                   class="form-control @error('name') is-invalid @enderror"
                   value="{{ old('name') }}">

            @error('name')
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
                                   @checked(is_array(old('permissions')) && in_array($permission->name, old('permissions', [])))>

                            <label class="form-check-label" for="perm_{{ $permission->id }}">
                                {{ $permission->name }}
                            </label>
                        </div>

                    </div>
                @endforeach

            </div>

            @error('permissions')
                <div class="text-danger small mt-1">{{ $message }}</div>
            @enderror
        </div>

        <div class="d-flex justify-content-end gap-2">

            <a href="{{ route('admin.roles.index') }}" class="btn btn-outline-secondary">
                Retour
            </a>

            <button class="btn btn-primary">
                Enregistrer
            </button>

        </div>

    </form>

</div>
</x-admin-layout>