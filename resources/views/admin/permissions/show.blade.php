<x-admin-layout>
<div class="container py-4">

    <h3>Détail permission</h3>

    <div class="card">
        <div class="card-body">

            <p><strong>Code :</strong> <code>{{ $permission->name }}</code></p>

            <p><strong>Label :</strong> {{ $permission->label ?? $permission->name }}</p>

            <p><strong>Module :</strong>
                @if($permission->module)
                    {{ $permission->module->label ?? $permission->module->name }}
                @else
                    <span class="text-muted">Aucun</span>
                @endif
            </p>

            <hr>

            <div class="d-flex gap-2">

                @can(\App\Permissions\PermissionPermissions::UPDATE)
                <a href="{{ route('admin.permissions.edit', $permission->id) }}"
                   class="btn btn-warning">
                    Modifier
                </a>
                @endcan

                <a href="{{ route('admin.permissions.index') }}"
                   class="btn btn-secondary">
                    Retour
                </a>

            </div>

        </div>
    </div>

</div>
</x-admin-layout>