<div class="mb-3">
    <label class="form-label">Nom du rôle</label>

    <input type="text"
           name="name"
           class="form-control @error('name') is-invalid @enderror"
           value="{{ old('name', $role->name ?? '') }}">

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
                           @checked(
                                is_array(old('permissions', $rolePermissions ?? []))
                                && in_array($permission->name, old('permissions', $rolePermissions ?? []))
                           )>

                    <label class="form-check-label"
                           for="perm_{{ $permission->id }}">
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