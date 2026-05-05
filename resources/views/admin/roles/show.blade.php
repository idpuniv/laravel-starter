@section('title', 'Role '. $role->label)
<x-admin-layout>
    <div class="container py-4">

        <div class="mb-4">
            <a href="{{ route('admin.roles.index') }}" class="btn btn-sm btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> Retour
            </a>
        </div>

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="mb-0">Détail du rôle <strong>{{ $role->label ?? $role->name }}</strong></h3>
        </div>

        <div class="card border-0">
            <div class="card-body">

                @if ($role->permissions->isNotEmpty())
                    <div class="mb-3">
                        <strong>Permissions :</strong>
                    </div>
                    <div class="d-flex flex-wrap gap-2">
                        @foreach ($role->permissions as $permission)
                            <span class="badge bg-primary">
                                {{ $permission->label ?? $permission->name }}
                            </span>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-4">
                        <div class="icon-circle-lg mx-auto mb-3">
                            <i class="bi bi-shield-slash fs-2"></i>
                        </div>
                        <p class="text-muted mb-3">Aucune permission associée à ce rôle</p>
                        @can(\App\Permissions\RolePermissions::UPDATE)
                        <a href="{{ route('admin.roles.edit', $role->id) }}" class="btn btn-primary">
                            <i class="bi bi-plus-lg"></i> Ajouter des permissions
                        </a>
                        @endcan
                    </div>
                @endif
            </div>
        </div>

    </div>
</x-admin-layout>
