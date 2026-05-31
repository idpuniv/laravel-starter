@section('title', 'Gestion des équipes')

<x-admin-layout>
    <div class="container py-4">

        {{-- En-tête --}}
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
            <div>
                <h1 class="h3 fw-semibold m-0 d-flex align-items-center gap-2">
                    Gestion des équipes
                </h1>
                <p class="text-muted small mt-1 mb-0">
                    {{ $teams->total() }} équipe(s) au total
                </p>
            </div>

            @can(\App\Permissions\TeamPermissions::CREATE)
                <a href="{{ route('admin.teams.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-lg me-2"></i>Créer une équipe
                </a>
            @endcan
        </div>

        {{-- Table --}}
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
                                        Équipe
                                    </span>
                                </th>

                                <th class="py-3">
                                    <span class="text-muted small fw-semibold">
                                        Membres
                                    </span>
                                </th>

                                <th class="py-3">
                                    <span class="text-muted small fw-semibold">
                                        Statut
                                    </span>
                                </th>

                                <th class="pe-4 py-3 text-end" width="120">
                                    <span class="text-muted small fw-semibold">Actions</span>
                                </th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse($teams as $team)
                                <tr>

                                    {{-- ID --}}
                                    <td class="ps-4">
                                        <span class="badge bg-secondary bg-opacity-10 text-secondary px-2 py-1 rounded-pill">
                                            #{{ $team->id }}
                                        </span>
                                    </td>

                                    {{-- TEAM --}}
                                    <td>
                                        <div class="d-flex align-items-center gap-2">

                                            <div class="rounded-circle bg-primary bg-opacity-10 p-2">
                                                @if($team->icon)
                                                    <i class="bi {{ $team->icon }} text-primary"></i>
                                                @else
                                                    <i class="bi bi-people text-primary"></i>
                                                @endif
                                            </div>

                                            <div>
                                                <div class="fw-semibold">
                                                    {{ $team->label }}
                                                </div>

                                                <div class="text-muted small">
                                                    {{ $team->name }}
                                                </div>
                                            </div>

                                        </div>
                                    </td>

                                    {{-- MEMBRES --}}
                                    <td>
                                        <a href="{{ route('admin.teams.users.index', $team->id) }}" 
                                           class="text-decoration-none">
                                            <span class="badge bg-info bg-opacity-10 text-info px-3 py-2 rounded-pill">
                                                <i class="bi bi-people-fill me-1"></i>
                                                {{ $team->users_count }} membre(s)
                                            </span>
                                        </a>
                                    </td>

                                    {{-- STATUS --}}
                                    <td>
                                        @if($team->status === 'active')
                                            <span class="badge bg-success bg-opacity-10 text-success px-3 py-2 rounded-pill">
                                                <i class="bi bi-check-circle"></i> Actif
                                            </span>
                                        @else
                                            <span class="badge bg-secondary bg-opacity-10 text-secondary px-3 py-2 rounded-pill">
                                                <i class="bi bi-pause-circle"></i> Inactif
                                            </span>
                                        @endif
                                    </td>

                                    {{-- ACTIONS --}}
                                    <td>
                                        <div class="d-flex gap-2 justify-content-end">

                                            @can(\App\Permissions\TeamPermissions::VIEW)
                                                <a href="{{ route('admin.teams.show', $team->id) }}"
                                                   class="icon-circle-xs text-decoration-none text-body hover-bg-secondary-25"
                                                   title="Voir les détails">
                                                    <i class="bi bi-eye fs-6"></i>
                                                </a>
                                            @endcan

                                            @can(\App\Permissions\TeamPermissions::UPDATE)
                                                <a href="{{ route('admin.teams.edit', $team->id) }}"
                                                   class="icon-circle-xs text-decoration-none text-body hover-bg-secondary-25"
                                                   title="Modifier">
                                                    <i class="bi bi-pencil fs-6"></i>
                                                </a>
                                            @endcan

                                            {{-- Lien vers les membres --}}
                                            @can(\App\Permissions\UserPermissions::UPDATE_TEAM)
                                                <a href="{{ route('admin.teams.users.create', $team->id) }}"
                                                   class="icon-circle-xs text-decoration-none text-body hover-bg-secondary-25"
                                                   title="Gérer les membres">
                                                    <i class="bi bi-person-plus fs-6"></i>
                                                </a>
                                            @endcan

                                            @can(\App\Permissions\TeamPermissions::DELETE)
                                                <a href="#"
                                                   class="icon-circle-xs text-decoration-none text-body hover-bg-secondary-25"
                                                   data-bs-toggle="modal"
                                                   data-bs-target="#confirmModal"
                                                   data-url="{{ route('admin.teams.destroy', $team->id) }}"
                                                   data-method="DELETE"
                                                   title="Supprimer">
                                                    <i class="bi bi-trash fs-6"></i>
                                                </a>
                                            @endcan

                                        </div>
                                    </td>

                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-5">

                                        <i class="bi bi-people fs-1 text-muted d-block mb-2"></i>

                                        <p class="text-muted mb-0">
                                            Aucune équipe créée
                                        </p>

                                        @can(\App\Permissions\TeamPermissions::CREATE)
                                            <a href="{{ route('admin.teams.create') }}"
                                               class="btn btn-sm btn-primary mt-3">
                                                <i class="bi bi-plus-lg"></i> Créer une équipe
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
        @if ($teams->hasPages())
            <div class="mt-4 d-flex justify-content-end">
                {{ $teams->links() }}
            </div>
        @endif

    </div>
</x-admin-layout>