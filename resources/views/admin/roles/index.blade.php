@section('title', 'Gestion des rôles')
<x-admin-layout>
    <div class="container py-4">

        {{-- En-tête avec statistiques --}}
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
            <div>
                <h1 class="h3 fw-semibold m-0 d-flex align-items-center gap-2">
                    Gestion des rôles
                </h1>
                <p class="text-muted small mt-1 mb-0">
                    {{ $roles->total() }} rôle(s) au total
                </p>
            </div>
            @can(\App\Permissions\RolePermissions::CREATE)
                <a href="{{ route('admin.roles.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-lg me-2"></i>Créer un rôle
                </a>
            @endcan
        </div>

        {{-- Table des rôles --}}
        <div class="card shadow-sm border-0 rounded-4">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead>
                            <tr>
                                <th class="ps-4 py-3" width="80">
                                    <span class="text-muted small fw-semibold">#ID</span>
                                </th>
                                <th class="py-3">
                                    <span class="text-muted small fw-semibold">
                                        Rôle
                                    </span>
                                </th>
                                <th class="py-3">
                                    <span class="text-muted small fw-semibold">
                                        Permissions
                                    </span>
                                </th>
                                <th class="pe-4 py-3 text-end" width="120">
                                    <span class="text-muted small fw-semibold">Actions</span>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($roles as $role)
                                <tr>
                                    <td class="ps-4">
                                        <span
                                            class="badge bg-secondary bg-opacity-10 text-secondary px-2 py-1 rounded-pill">
                                            #{{ $role->id }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="rounded-circle bg-primary bg-opacity-10 p-2">
                                                <i class="bi bi-shield-check text-primary"></i>
                                            </div>
                                            <div>
                                                <div class="fw-semibold">{{ $role->label }}</div>
                                                <div class="text-muted small">{{ $role->name }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex flex-wrap gap-1">
                                            @php
                                                $permissions = $role->permissions->take(5);
                                                $remaining = $role->permissions->count() - 5;
                                            @endphp

                                            @foreach ($permissions as $permission)
                                                <span class="badge border text-body px-2 py-1 rounded-pill small">
                                                    {{ $permission->label }}
                                                </span>
                                            @endforeach

                                            @if ($remaining > 0)
                                                <span
                                                    class="badge bg-secondary bg-opacity-10 text-secondary px-2 py-1 rounded-pill small">
                                                    +{{ $remaining }} autre(s)
                                                </span>
                                            @endif

                                            @if ($role->permissions->isEmpty())
                                                <span class="text-muted fst-italic small">Aucune permission</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex gap-2 justify-content-end">
                                            @can(\App\Permissions\RolePermissions::VIEW)
                                                <a href="{{ route('admin.roles.show', $role->id) }}"
                                                    class="icon-circle-xs text-decoration-none text-body hover-bg-secondary-25"
                                                    title="Voir les détails">
                                                    <i class="bi bi-eye fs-6"></i>
                                                </a>
                                            @endcan

                                            @can(\App\Permissions\RolePermissions::UPDATE)
                                                <a href="{{ route('admin.roles.edit', $role->id) }}"
                                                    class="icon-circle-xs text-decoration-none text-body hover-bg-secondary-25"
                                                    title="Modifier le rôle">
                                                    <i class="bi bi-pencil fs-6"></i>
                                                </a>
                                            @endcan

                                            @can(\App\Permissions\RolePermissions::DELETE)
                                                @if (!$role->is_default)
                                                    <a href="#"
                                                        class="icon-circle-xs text-decoration-none text-body hover-bg-secondary-25"
                                                        data-bs-toggle="modal" data-bs-target="#confirmModal"
                                                        data-url="{{ route('admin.roles.destroy', $role->id) }}"
                                                        data-method="DELETE" title="Supprimer le rôle">
                                                        <i class="bi bi-trash fs-6"></i>
                                                        <span class="visually-hidden">Supprimer</span>
                                                    </a>
                                                @else
                                                    <span class="text-muted" title="Rôle système non supprimable">
                                                        <i class="bi bi-trash fs-6"></i>
                                                    </span>
                                                @endif
                                            @endcan
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center py-5">
                                        <i class="bi bi-shield-slash fs-1 text-muted d-block mb-2"></i>
                                        <p class="text-muted mb-0">Aucun rôle n'a été créé</p>
                                        @can(\App\Permissions\RolePermissions::CREATE)
                                            <a href="{{ route('admin.roles.create') }}"
                                                class="btn btn-sm btn-primary mt-3">
                                                <i class="bi bi-plus-lg"></i> Créer votre premier rôle
                                            </a>
                                        @endcan
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Pagination --}}
        @if ($roles->hasPages())
            <div class="mt-4 d-flex justify-content-end">
                {{ $roles->links() }}
            </div>
        @endif

    </div>
</x-admin-layout>
