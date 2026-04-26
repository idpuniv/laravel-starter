<x-admin-layout>
<div class="container py-4">

    <h3>Modifier le rôle</h3>

    <form action="{{ route('admin.roles.update', $role->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label class="form-label">Nom du rôle</label>
            <input type="text" name="name" class="form-control"
                   value="{{ old('name', $role->name) }}">
        </div>

        <div class="mb-3">
            <label class="form-label">Permissions</label>
            <div class="row">
                @foreach($permissions as $permission)
                    <div class="col-md-3">
                        <div class="form-check">
                            <input class="form-check-input"
                                   type="checkbox"
                                   name="permissions[]"
                                   value="{{ $permission->name }}"
                                   {{ $role->permissions->contains('name', $permission->name) ? 'checked' : '' }}>
                            <label class="form-check-label">
                                {{ $permission->name }}
                            </label>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <button class="btn btn-primary">Mettre à jour</button>
        <a href="{{ route('admin.roles.index') }}" class="btn btn-secondary">Retour</a>
    </form>

</div>
</x-admin-layout>