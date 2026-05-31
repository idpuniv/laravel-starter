@section('title', $person->first_name . ' ' . $person->last_name)
<x-admin-layout>
    <div class="container py-4">

        {{-- En-tête avec actions --}}
        <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 mb-0 d-flex align-items-center gap-2">
                    <i class="bi bi-person-badge text-primary"></i>
                    {{ $person->first_name }} {{ $person->last_name }}
                    <small class="text-muted fs-6 fw-normal">#{{ $person->id }}</small>
                </h1>
            </div>
            <div class="mt-3 mt-sm-0">
                <a href="{{ route('admin.people.index') }}" class="btn btn-outline-secondary me-2">
                    <i class="bi bi-arrow-left"></i> Retour
                </a>
                @can(\App\Permissions\PersonPermissions::UPDATE)
                @if($person->user)
                <a href="{{ route('admin.people.edit', $person->id) }}" class="btn btn-primary">
                    <i class="bi bi-pencil-square"></i> Modifier
                </a>
                @endif
                @endcan
            </div>
        </div>

        {{-- Section Photo de profil en haut --}}

        {{-- Carte informations personnelles --}}
        <div class="card shadow-sm border-0 rounded-4 mb-4 pt-3">
            @include('admin.people.partials.profile-photo')
            <div class="card-header bg-transparent border-bottom-0 pt-4 px-4">
                <div class="d-flex align-items-center gap-2">
                    <h5 class="mb-0">Informations personnelles</h5>
                </div>
            </div>
            <div class="card-body p-4 pt-0">
                <div class="row g-4">
                    <div class="col-md-6">
                        <div class="rounded-3 p-3 h-100">
                            <div class="text-muted small mb-2">
                                <i class="bi bi-person"></i> Prénom
                            </div>
                            <div class="fs-5 fw-semibold">{{ $person->first_name }}</div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="rounded-3 p-3 h-100">
                            <div class="text-muted small mb-2">
                                <i class="bi bi-person"></i> Nom
                            </div>
                            <div class="fs-5 fw-semibold">{{ $person->last_name }}</div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="rounded-3 p-3 h-100">
                            <div class="text-muted small mb-2">
                                <i class="bi bi-telephone"></i> Téléphone
                            </div>
                            <div class="fs-6 fw-semibold">
                                @if($person->full_phone)
                                <a href="tel:{{ $person->full_phone }}" class="text-decoration-none">
                                    {{ $person->full_phone }}
                                </a>
                                @else
                                <span class="text-muted">-</span>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="rounded-3 p-3 h-100">
                            <div class="text-muted small mb-2">
                                <i class="bi bi-gender-ambiguous"></i> Genre
                            </div>
                            <div class="fs-6 fw-semibold">
                                @if($person->gender === 'male')
                                <i class="bi bi-gender-male text-primary"></i> Masculin
                                @elseif($person->gender === 'female')
                                <i class="bi bi-gender-female text-danger"></i> Féminin
                                @else
                                -
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="rounded-3 p-3 h-100">
                            <div class="text-muted small mb-2">
                                <i class="bi bi-geo-alt"></i> Pays
                            </div>
                            <div class="fs-6 fw-semibold">
                                @if($person->country)
                                <i class="bi bi-flag"></i> {{ $person->country->name }}
                                @else
                                -
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="rounded-3 p-3 h-100">
                            <div class="text-muted small mb-2">
                                <i class="bi bi-calendar3"></i> Date de création
                            </div>
                            <div class="fs-6 fw-semibold">
                                {{ $person->created_at->format('d/m/Y à H:i') }}
                                <span class="text-muted small">({{ $person->created_at->diffForHumans() }})</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Carte compte utilisateur --}}
        <div class="card shadow-sm border-0 rounded-4">
            <div class="card-header bg-transparent border-bottom-0 pt-4 px-4">
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <div class="d-flex align-items-center gap-2">
                        <i class="bi bi-person-check text-primary fs-4"></i>
                        <h5 class="mb-0">Compte utilisateur</h5>
                    </div>
                    @can(\App\Permissions\UserPermissions::CREATE)
                    @if(!$person->user)
                    <a href="{{ route('admin.people.show-add-user-form', $person->id) }}" class="btn btn-sm btn-primary">
                        <i class="bi bi-plus-lg"></i> Créer un compte
                    </a>
                    @endif
                    @endcan
                </div>
            </div>
            <div class="card-body p-4 pt-0">
                @if($person->user)
                <div class="row g-4">
                    <div class="col-md-6">
                        <div class="rounded-3 p-3">
                            <div class="text-muted small mb-2">
                                <i class="bi bi-person-badge"></i> Nom d'utilisateur
                            </div>
                            <div class="fs-6 fw-semibold">
                                <code>{{ $person->user->username ?? '-' }}</code>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="rounded-3 p-3">
                            <div class="text-muted small mb-2">
                                <i class="bi bi-envelope"></i> Email
                            </div>
                            <div class="fs-6 fw-semibold">
                                <a href="mailto:{{ $person->user->email }}" class="text-decoration-none">
                                    {{ $person->user->email }}
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="rounded-3 p-3">
                            <div class="text-muted small mb-2">
                                Statut
                            </div>
                            <div>
                                @if($person->user->status === 'active')
                                <span class="badge bg-success px-3 py-2">
                                    <i class="bi bi-check-circle"></i> Actif
                                </span>
                                @elseif($person->user->status === 'inactive')
                                <span class="badge bg-secondary px-3 py-2">
                                    <i class="bi bi-pause-circle"></i> Inactif
                                </span>
                                @elseif($person->user->status === 'banned')
                                <span class="badge bg-danger px-3 py-2">
                                    <i class="bi bi-exclamation-octagon"></i> Banni
                                </span>
                                @endif
                            </div>
                        </div>
                    </div>
                    @if(config('permission.teams'))
                    <div class="col-md-6">
                        <div class="rounded-3 p-3">
                            <div class="text-muted small mb-2">
                                <i class="bi bi-people"></i> Équipe
                            </div>
                            <div class="fs-6 fw-semibold">
                                {{ $person->user->team->name ?? '-' }}
                            </div>
                        </div>
                    </div>
                    @endif
                </div>

                {{-- Rôles --}}
                <div class="mt-4 pt-3 border-top">
                    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
                        <div class="d-flex align-items-center gap-2">
                            <i class="bi bi-shield-lock text-primary"></i>
                            <strong class="mb-0">Rôles</strong>
                        </div>
                        @can(\App\Permissions\UserPermissions::UPDATE_ROLE)
                        <a href="{{ route('admin.teams.index') }}" class="btn btn-sm btn-outline-primary">
                            <i class="bi bi-pencil"></i> Gérer les rôles
                        </a>
                        @endcan
                    </div>
                    <div class="d-flex flex-wrap gap-2">
                        @forelse($person->user->roles as $role)
                        <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 rounded-pill">
                            <i class="bi bi-shield-check"></i> {{ $role->label }}
                        </span>
                        @empty
                        <span class="text-muted">Aucun rôle assigné</span>
                        @endforelse
                    </div>
                </div>

                {{-- Permissions directes --}}
                <div class="mt-4 pt-3 border-top">
                    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
                        <div class="d-flex align-items-center gap-2">
                            <i class="bi bi-key text-primary"></i>
                            <strong class="mb-0">Permissions directes</strong>
                        </div>
                        @can(\App\Permissions\UserPermissions::UPDATE_PERMISSION)
                        <a href="{{ route('admin.teams.index') }}" class="btn btn-sm btn-outline-primary">
                            <i class="bi bi-pencil"></i> Gérer les permissions
                        </a>
                        @endcan
                    </div>
                    <div class="d-flex flex-wrap gap-2">
                        @forelse($person->user->permissions as $permission)
                        <span class="badge bg-secondary bg-opacity-10 text-secondary px-3 py-2 rounded-pill">
                            <i class="bi bi-check-lg"></i> {{ $permission->label }}
                        </span>
                        @empty
                        <span class="text-muted">Aucune permission directe</span>
                        @endforelse
                    </div>
                </div>
                @else
                <div class="alert alert-info border-0 bg-info bg-opacity-10 rounded-3 mb-0">
                    <div class="d-flex gap-3">
                        <i class="bi bi-info-circle-fill fs-4"></i>
                        <div>
                            <h6 class="mb-1">Aucun compte associé</h6>
                            <p class="mb-0 small">Cet utilisateur n'a pas encore de compte. Cliquez sur "Créer un compte" pour en ajouter un.</p>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>

        @if(config('permission.teams'))
        <div class="mt-4 pt-3 border-top">

            <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">

                <div class="d-flex align-items-center gap-2">
                    <i class="bi bi-people text-primary"></i>
                    <strong class="mb-0">Gestion des équipes</strong>
                </div>

                @if($person->user)
                @can(\App\Permissions\UserPermissions::UPDATE_TEAM)
                <a href=""
                    class="btn btn-sm btn-outline-primary">
                    <i class="bi bi-gear"></i> Gérer
                </a>
                @endcan
                @endif

            </div>

            @if(!$person->user)
            <div class="alert alert-info border-0 bg-info bg-opacity-10 rounded-3 mb-0">
                <div class="d-flex gap-3">
                    <i class="bi bi-info-circle-fill fs-4"></i>
                    <div>
                        <h6 class="mb-1">Aucun compte utilisateur</h6>
                        <p class="mb-0 small">
                            Impossible d’assigner des équipes sans compte utilisateur.
                        </p>
                    </div>
                </div>
            </div>
            @else

            {{-- AFFICHAGE DES TEAMS --}}
            <div class="mt-2">

                @if($person->user->teams->count() > 0)
                <div class="d-flex flex-wrap gap-2">

                    @foreach($person->user->teams as $team)
                    <span class="badge bg-primary-subtle text-primary border">
                        {{ $team->label }}
                    </span>
                    @endforeach

                </div>
                @else
                <div class="text-muted small">
                    Aucun équipe assignée à cet utilisateur.
                </div>
                @endif

            </div>

            @endif

        </div>
        @endif
    </div>
</x-admin-layout>