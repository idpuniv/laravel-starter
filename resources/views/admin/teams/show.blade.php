@section('title', $team->label . ' - Détails de l\'équipe')

<x-admin-layout>
    <div class="container py-4">

        {{-- En-tête avec actions --}}
        <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 fw-semibold m-0 d-flex align-items-center gap-2">
                    @if($team->icon)
                        <i class="bi {{ $team->icon }} text-primary"></i>
                    @else
                        <i class="bi bi-people text-primary"></i>
                    @endif
                    {{ $team->label }}
                </h1>
                <p class="text-muted small mt-1 mb-0">
                    {{ $team->name }} • {{ $team->users_count }} membre(s)
                </p>
            </div>
            <div class="mt-3 mt-sm-0">
                <a href="{{ route('admin.teams.index') }}" class="btn btn-outline-secondary me-2">
                    <i class="bi bi-arrow-left"></i> Retour
                </a>
                @can(\App\Permissions\TeamPermissions::UPDATE)
                    <a href="{{ route('admin.teams.edit', $team->id) }}" class="btn btn-primary">
                        <i class="bi bi-pencil-square"></i> Modifier
                    </a>
                @endcan
            </div>
        </div>

        {{-- Informations générales --}}
        <div class="card shadow-sm border-0 rounded-4 mb-4">
            <div class="card-header bg-transparent border-bottom-0 pt-4 px-4">
                <div class="d-flex align-items-center gap-2">
                    <i class="bi bi-info-circle text-primary fs-5"></i>
                    <h5 class="mb-0">Informations générales</h5>
                </div>
            </div>
            <div class="card-body p-4 pt-0">
                <div class="row g-4">
                    <div class="col-md-6">
                        <div class="rounded-3 p-3 bg-light bg-opacity-50">
                            <div class="text-muted small mb-2">
                                <i class="bi bi-tag"></i> Nom technique
                            </div>
                            <div class="fs-6 fw-semibold">
                                <code>{{ $team->name }}</code>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="rounded-3 p-3 bg-light bg-opacity-50">
                            <div class="text-muted small mb-2">
                                <i class="bi bi-palette"></i> Icône
                            </div>
                            <div class="fs-6 fw-semibold">
                                @if($team->icon)
                                    <code>{{ $team->icon }}</code>
                                    <i class="bi {{ $team->icon }} ms-2"></i>
                                @else
                                    <span class="text-muted">Aucune</span>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="rounded-3 p-3 bg-light bg-opacity-50">
                            <div class="text-muted small mb-2">
                                <i class="bi bi-calendar3"></i> Date de création
                            </div>
                            <div class="fs-6 fw-semibold">
                                {{ $team->created_at->format('d/m/Y à H:i') }}
                                <span class="text-muted small">({{ $team->created_at->diffForHumans() }})</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="rounded-3 p-3 bg-light bg-opacity-50">
                            <div class="text-muted small mb-2">
                            </div>
                            <div>
                                @if($team->status === 'active')
                                    <span class="badge bg-success bg-opacity-10 text-success px-3 py-2 rounded-pill">
                                        <i class="bi bi-check-circle"></i> Actif
                                    </span>
                                @else
                                    <span class="badge bg-secondary bg-opacity-10 text-secondary px-3 py-2 rounded-pill">
                                        <i class="bi bi-pause-circle"></i> Inactif
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                    @if($team->description)
                        <div class="col-12">
                            <div class="rounded-3 p-3 bg-light bg-opacity-50">
                                <div class="text-muted small mb-2">
                                    <i class="bi bi-file-text"></i> Description
                                </div>
                                <div class="fs-6">
                                    {{ $team->description }}
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Liste des membres --}}
        <div class="card shadow-sm border-0 rounded-4">
            <div class="card-header bg-transparent border-bottom-0 pt-4 px-4">
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <div class="d-flex align-items-center gap-2">
                        <i class="bi bi-people text-primary fs-5"></i>
                        <h5 class="mb-0">Membres de l'équipe</h5>
                    </div>
                    @can(\App\Permissions\TeamPermissions::UPDATE)
                        <a href="{{ route('admin.teams.users.create', $team->id) }}" class="btn btn-sm btn-primary">
                            <i class="bi bi-plus-lg"></i> Ajouter
                        </a>
                    @endcan
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead>
                            <tr>
                                <th class="ps-4 py-3">
                                    <span class="text-muted small fw-semibold">Utilisateur</span>
                                </th>
                                <th class="py-3">
                                    <span class="text-muted small fw-semibold">Email</span>
                                </th>
                                <th class="pe-4 py-3 text-end" width="120">
                                    <span class="text-muted small fw-semibold">Actions</span>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($team->users as $user)
                                <tr>
                                    <td class="ps-4">
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="rounded-circle bg-primary bg-opacity-10 p-2">
                                                <i class="bi bi-person-circle text-primary"></i>
                                            </div>
                                            <div>
                                                <div class="fw-semibold">{{ $user->name }}</div>
                                                @if($user->person)
                                                    <div class="text-muted small">{{ $user->person->fullName ?? '' }}</div>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $user->email }}</td>
                                    <td class="pe-4 py-3 text-end">
                                        <div class="d-flex gap-2 justify-content-end">
                                            @can(\App\Permissions\TeamPermissions::UPDATE)
                                                <a href="#"
                                                   class="icon-circle-xs text-decoration-none text-body hover-bg-secondary-25"
                                                   data-bs-toggle="modal"
                                                   data-bs-target="#confirmModal"
                                                   data-url="{{ route('admin.teams.users.destroy', [$team, $user]) }}"
                                                   data-method="DELETE"
                                                   title="Retirer">
                                                    <i class="bi bi-person-dash fs-6"></i>
                                                </a>
                                            @endcan
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center py-5">
                                        <i class="bi bi-people fs-1 text-muted d-block mb-2"></i>
                                        <p class="text-muted mb-0">Aucun membre dans cette équipe</p>
                                        @can(\App\Permissions\TeamPermissions::UPDATE)
                                            <a href="{{ route('admin.teams.users.create', $team->id) }}" class="btn btn-sm btn-primary mt-3">
                                                <i class="bi bi-plus-lg"></i> Ajouter des membres
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

    </div>
</x-admin-layout>