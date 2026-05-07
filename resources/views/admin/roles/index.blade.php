@section('title', 'Liste des roles')
<x-admin-layout>
    <div class="container py-4">

        {{-- En-tête --}}
        <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-3 mb-4">
            <h1 class="h3 fw-semibold m-0">
                Liste des rôles
            </h1>
            @can(\App\Permissions\RolePermissions::CREATE)
                <a href="{{ route('admin.roles.create') }}" class="btn btn-primary align-self-start align-self-sm-auto">
                    <i class="bi bi-plus-lg me-2"></i>Nouveau rôle
                </a>
            @endcan
        </div>

        {{-- Carte --}}
        <div class="card">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle m-0 overlay-primary">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Nom</th>
                                <th scope="col">Permissions</th>
                                <th scope="col">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($roles as $role)
                                <tr>
                                    <td class="fw-semibold">{{ $role->id }}</td>
                                    <td>{{ $role->label }}</td>
                                    <td>
                                        @forelse($role->permissions as $permission)
                                            <span class="badge bg-secondary me-1 mb-1">
                                                {{ $permission->label }}
                                            </span>
                                        @empty
                                            <span class="text-muted fst-italic">Aucune permission</span>
                                        @endforelse
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @can(\App\Permissions\RolePermissions::VIEW)
                                                <a href="{{ route('admin.roles.show', $role->id) }}"
                                                    class="icon-circle-xs text-decoration-none text-body hover-bg-secondary-25"
                                                    title="Voir">
                                                    <i class="bi bi-eye"></i>
                                                    <span class="visually-hidden">Voir</span>
                                                </a>
                                            @endcan

                                            @can(\App\Permissions\RolePermissions::UPDATE)
                                                <a href="{{ route('admin.roles.edit', $role->id) }}"
                                                    class="icon-circle-xs text-decoration-none text-body hover-bg-secondary-25"
                                                    title="Modifier">
                                                    <i class="bi bi-pencil"></i>
                                                    <span class="visually-hidden">Modifier</span>
                                                </a>
                                            @endcan

                                            @can(\App\Permissions\RolePermissions::DELETE)
                                                <a class="dropdown-item" href="#" data-bs-toggle="modal"
                                                    data-bs-target="#deleteModal"
                                                    data-url="{{ route('admin.roles.destroy', $role->id) }}">{{ __('Supprimer') }}</a>
                                            @endcan
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center py-5 text-muted">
                                        <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                        Aucun rôle existant
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Pagination --}}
        <div class="mt-4">
            {{ $roles->links() }}
        </div>

    </div>
</x-admin-layout>
