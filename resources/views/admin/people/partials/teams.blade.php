
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
                                    <i class="bi bi-people text-primary"></i>
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

                        {{-- STATUS --}}
                        <td>
                            @if($team->status === 'active')
                            <span class="badge bg-success bg-opacity-10 text-success px-2 py-1 rounded-pill">
                                Actif
                            </span>
                            @else
                            <span class="badge bg-secondary bg-opacity-10 text-secondary px-2 py-1 rounded-pill">
                                Inactif
                            </span>
                            @endif
                        </td>

                        {{-- ACTIONS --}}
                        <td>
                            <div class="d-flex gap-2 justify-content-end">

                        @can(\App\Permissions\UserPermissions::VIEW_TEAM)
                                <a href="{{ route('admin.users.teams.edit', ['user' => $user, 'team' => $team]) }}"
                                    class="icon-circle-xs text-decoration-none text-body hover-bg-secondary-25"
                                    title="Voir les détails">
                                    <i class="bi bi-eye fs-6"></i>
                                </a>
                                @endcan

                                @can(\App\Permissions\UserPermissions::UPDATE_TEAM)
                                <a href="{{ route('admin.users.teams.edit', ['user' => $user, 'team' => $team]) }}"
                                    class="icon-circle-xs text-decoration-none text-body hover-bg-secondary-25"
                                    title="Modifier">
                                    <i class="bi bi-pencil fs-6"></i>
                                </a>
                                @endcan

                                @can(\App\Permissions\UserPermissions::DELETE_TEAM)
                                <a href="#"
                                    class="icon-circle-xs text-decoration-none text-body hover-bg-secondary-25"
                                    data-bs-toggle="modal"
                                    data-bs-target="#confirmModal"
                                    data-url="{{ route('admin.users.teams.destroy', ['user' => $user, 'team' => $team]) }}"
                                    data-method="DELETE"
                                    title="Retirer du groupe">
                                    <i class="bi bi-person-x fs-6"></i>
                                </a>
                                @endcan

                            </div>
                        </td>

                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-5">

                            <i class="bi bi-people fs-1 text-muted d-block mb-2"></i>

                            <p class="text-muted mb-0">
                                Aucune équipe créée
                            </p>

                            @can(\App\Permissions\UserPermissions::CREATE_TEAM)
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