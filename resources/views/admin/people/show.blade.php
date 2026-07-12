@section('title', $person->first_name . ' ' . $person->last_name)

<x-admin-layout>
    <div class="container py-4">
        <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
            <x-breadcrumb></x-breadcrumb>
            <div class="d-flex align-items-center gap-2 mt-2 mt-sm-0">
                @can(\App\Permissions\PersonPermissions::UPDATE)
                @if($person->user)
                <a href="{{ route('admin.people.edit', $person->id) }}"
                    class="btn btn-link text-body icon-circle-xs text-decoration-none hover-bg-secondary-25 p-0 m-0">
                    <i class="bi bi-pencil"></i>
                </a>
                @endif
                @endcan

                @can(\App\Permissions\PersonPermissions::DELETE)
                <button type="button"
                    class="btn btn-link text-danger icon-circle-xs text-decoration-none hover-bg-secondary-25 p-0 m-0"
                    data-bs-toggle="modal"
                    data-bs-target="#confirmModal"
                    data-url="{{ route('admin.people.destroy', $person->id) }}"
                    data-method="DELETE">
                    <i class="bi bi-trash"></i>
                </button>
                @endcan
            </div>
        </div>

        {{-- Profile hero --}}
        <div class="card border-0 shadow-sm rounded-4 mb-4">
            <div class="card-body p-4">
                <div class="d-flex align-items-center gap-4 flex-wrap">
                    {{-- ==========================================
                 AVATAR & STATUT EN LIGNE (PASTILLE)
                 ========================================== --}}
                    <div class="position-relative flex-shrink-0" style="width:72px;height:72px;">
                        <div class="rounded-circle bg-primary-subtle border border-primary-subtle d-flex align-items-center justify-content-center w-100 h-100 overflow-hidden"
                            style="font-size:1.75rem;">
                            @if($person->photo_url)
                            <img src="{{ $person->photo_url }}"
                                alt="{{ $person->first_name }}"
                                class="w-100 h-100 object-fit-cover">
                            @else
                            <i class="bi bi-person text-primary"></i>
                            @endif
                        </div>

                        {{-- Online dot: shown only while the user has an active session --}}
                        @if($person->user?->is_logged)
                        <span class="person-online-dot position-absolute rounded-circle"
                            style="width:16px;height:16px;bottom:2px;right:2px;background:#28a745;border:3px solid var(--bs-body-bg,#fff);"
                            title="{{ __('En ligne') }}">
                        </span>
                        @endif
                    </div>

                    {{-- ==========================================
                 METADONNÉES DE LA PERSONNE
                 ========================================== --}}
                    <div>
                        <h2 class="h5 fw-bold mb-1">
                            {{ $person->first_name }} {{ $person->last_name }}
                            <span class="badge text-bg-secondary fw-normal fs-xs ms-1">
                                #{{ $person->id }}
                            </span>
                        </h2>

                        <div class="d-flex flex-wrap gap-3 text-body-secondary small">
                            {{-- Gender --}}
                            @if($person->gender === 'male')
                            <span><i class="bi bi-gender-male me-1"></i>{{ __('Masculin') }}</span>
                            @elseif($person->gender === 'female')
                            <span><i class="bi bi-gender-female me-1"></i>{{ __('Féminin') }}</span>
                            @endif

                            {{-- Country --}}
                            @if($person->country)
                            <span><i class="bi bi-geo-alt me-1"></i>{{ $person->country->name }}</span>
                            @endif

                            {{-- Record age --}}
                            <span><i class="bi bi-clock me-1"></i>{{ $person->created_at->diffForHumans() }}</span>

                            {{-- Last seen: shown only when the user is NOT currently online --}}
                            @if($person->user && !$person->user->is_logged)
                            <span>
                                <i class="bi bi-box-arrow-in-right me-1"></i>
                                {{ __('Connexion :') }}
                                {{ $person->user->last_login_at?->diffForHumans(short: true) ?? __('Jamais') }}
                            </span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Personal info --}}
        <div class="card border-0 shadow-sm rounded-4 mb-4">
            <div class="card-header bg-transparent border-bottom px-4 pt-4 pb-3">
                <h6 class="mb-0 fw-semibold d-flex align-items-center gap-2">
                    <i class="bi bi-person-lines-fill text-primary"></i>
                    {{ __('Informations personnelles') }}
                </h6>
            </div>
            <div class="card-body p-4">
                <div class="row g-3">

                    <div class="col-md-6">
                        <div class="bg-body-tertiary rounded-3 p-3 h-100">
                            <p class="text-body-secondary small text-uppercase fw-medium mb-1 d-flex align-items-center gap-1">
                                <i class="bi bi-person"></i> {{ __('Prénom') }}
                            </p>
                            <p class="fw-semibold mb-0">{{ $person->first_name }}</p>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="bg-body-tertiary rounded-3 p-3 h-100">
                            <p class="text-body-secondary small text-uppercase fw-medium mb-1 d-flex align-items-center gap-1">
                                <i class="bi bi-person"></i> {{ __('Nom') }}
                            </p>
                            <p class="fw-semibold mb-0">{{ $person->last_name }}</p>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="bg-body-tertiary rounded-3 p-3 h-100">
                            <p class="text-body-secondary small text-uppercase fw-medium mb-1 d-flex align-items-center gap-1">
                                <i class="bi bi-telephone"></i> {{ __('Téléphone') }}
                            </p>
                            <p class="fw-semibold mb-0">
                                @if($person->full_phone)
                                <a href="tel:{{ $person->full_phone }}" class="text-decoration-none">
                                    {{ $person->full_phone }}
                                </a>
                                @else
                                <span class="text-body-tertiary">—</span>
                                @endif
                            </p>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="bg-body-tertiary rounded-3 p-3 h-100">
                            <p class="text-body-secondary small text-uppercase fw-medium mb-1 d-flex align-items-center gap-1">
                                <i class="bi bi-gender-ambiguous"></i> {{ __('Genre') }}
                            </p>
                            <p class="fw-semibold mb-0">
                                @if($person->gender === 'male')
                                <i class="bi bi-gender-male text-primary"></i> {{ __('Masculin') }}
                                @elseif($person->gender === 'female')
                                <i class="bi bi-gender-female text-danger"></i> {{ __('Féminin') }}
                                @else
                                <span class="text-body-tertiary">—</span>
                                @endif
                            </p>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="bg-body-tertiary rounded-3 p-3 h-100">
                            <p class="text-body-secondary small text-uppercase fw-medium mb-1 d-flex align-items-center gap-1">
                                <i class="bi bi-geo-alt"></i> {{ __('Pays') }}
                            </p>
                            <p class="fw-semibold mb-0">
                                @if($person->country)
                                <i class="bi bi-flag me-1"></i>{{ $person->country->name }}
                                @else
                                <span class="text-body-tertiary">—</span>
                                @endif
                            </p>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="bg-body-tertiary rounded-3 p-3 h-100">
                            <p class="text-body-secondary small text-uppercase fw-medium mb-1 d-flex align-items-center gap-1">
                                <i class="bi bi-calendar3"></i> {{ __('Créé le') }}
                            </p>
                            <p class="fw-semibold mb-0">
                                {{ $person->created_at->format('d/m/Y à H:i') }}
                                <small class="text-body-secondary fw-normal">
                                    ({{ $person->created_at->diffForHumans() }})
                                </small>
                            </p>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        {{-- User account --}}
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-header bg-transparent border-bottom px-4 pt-4 pb-3">
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <h6 class="mb-0 fw-semibold d-flex align-items-center gap-2">
                        <i class="bi bi-person-check text-primary"></i>
                        {{ __('Compte utilisateur') }}
                    </h6>
                    @can(\App\Permissions\UserPermissions::CREATE)
                    @if(!$person->user)
                    <a href="{{ route('admin.people.show-add-user-form', $person->id) }}"
                        class="btn btn-sm btn-primary">
                        <i class="bi bi-plus-lg me-1"></i>{{ __('Créer un compte') }}
                    </a>
                    @endif
                    @endcan
                </div>
            </div>

            <div class="card-body p-4">
                @if($person->user)

                <div class="row g-3">

                    <div class="col-md-6">
                        <div class="bg-body-tertiary rounded-3 p-3 h-100">
                            <p class="text-body-secondary small text-uppercase fw-medium mb-1 d-flex align-items-center gap-1">
                                <i class="bi bi-person-badge"></i> {{ __("Nom d'utilisateur") }}
                            </p>
                            <p class="fw-semibold mb-0">
                                <code>{{ $person->user->username ?? '—' }}</code>
                            </p>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="bg-body-tertiary rounded-3 p-3 h-100">
                            <p class="text-body-secondary small text-uppercase fw-medium mb-1 d-flex align-items-center gap-1">
                                <i class="bi bi-envelope"></i> {{ __('Email') }}
                            </p>
                            <p class="fw-semibold mb-0">
                                <a href="mailto:{{ $person->user->email }}" class="text-decoration-none">
                                    {{ $person->user->email }}
                                </a>
                            </p>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="bg-body-tertiary rounded-3 p-3 h-100">
                            <p class="text-body-secondary small text-uppercase fw-medium mb-1 d-flex align-items-center gap-1">
                                <i class="bi bi-activity"></i> {{ __('Statut') }}
                            </p>
                            <p class="mb-0">
                                @if($person->user->status === 'active')
                                <span class="badge text-bg-success">
                                    <i class="bi bi-check-circle me-1"></i>{{ __('Actif') }}
                                </span>
                                @elseif($person->user->status === 'inactive')
                                <span class="badge text-bg-secondary">
                                    <i class="bi bi-pause-circle me-1"></i>{{ __('Inactif') }}
                                </span>
                                @elseif($person->user->status === 'banned')
                                <span class="badge text-bg-danger">
                                    <i class="bi bi-x-circle me-1"></i>{{ __('Banni') }}
                                </span>
                                @endif
                            </p>
                        </div>
                    </div>

                    @if(config('permission.teams'))
                    <div class="col-md-6">
                        <div class="bg-body-tertiary rounded-3 p-3 h-100">
                            <p class="text-body-secondary small text-uppercase fw-medium mb-1 d-flex align-items-center gap-1">
                                <i class="bi bi-people"></i> {{ __('Équipe') }}
                            </p>
                            <p class="fw-semibold mb-0">
                                {{ $person->user->team?->name ?? '—' }}
                            </p>
                        </div>
                    </div>
                    @endif

                </div>

                {{-- Roles --}}
                <hr class="my-4">
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
                    <p class="fw-semibold mb-0 d-flex align-items-center gap-2">
                        <i class="bi bi-shield-lock text-primary"></i>{{ __('Rôles') }}
                    </p>
                    @can(\App\Permissions\UserPermissions::UPDATE_ROLE)
                    <a href="{{ route('admin.users.roles.edit', $person->user->id) }}"
                        class="btn btn-link text-body icon-circle-xs text-decoration-none hover-bg-secondary-25 p-0 m-0">
                        <i class="bi bi-pencil"></i>
                    </a>
                    @endcan
                </div>
                <div class="d-flex flex-wrap gap-2">
                    @forelse($person->user->roles as $role)
                    <span class="badge rounded-pill border border-primary-subtle text-primary bg-primary-subtle px-3 py-2">
                        <i class="bi bi-shield-check me-1"></i>{{ $role->label }}
                    </span>
                    @empty
                    <span class="text-body-secondary small">{{ __('Aucun rôle assigné') }}</span>
                    @endforelse
                </div>

                {{-- Direct permissions --}}
                <hr class="my-4">
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
                    <p class="fw-semibold mb-0 d-flex align-items-center gap-2">
                        <i class="bi bi-key text-primary"></i>{{ __('Permissions directes') }}
                    </p>
                    @can(\App\Permissions\UserPermissions::UPDATE_PERMISSION)
                    <a href="{{ route('admin.users.permissions.edit', $person->user->id) }}"
                        class="btn btn-link text-body icon-circle-xs text-decoration-none hover-bg-secondary-25 p-0 m-0">
                        <i class="bi bi-pencil"></i>
                    </a>
                    @endcan
                </div>
                <div class="d-flex flex-wrap gap-2">
                    @forelse($person->user->permissions as $permission)
                    <span class="badge rounded-pill border border-secondary-subtle text-secondary bg-secondary-subtle px-3 py-2">
                        <i class="bi bi-check-lg me-1"></i>{{ $permission->label }}
                    </span>
                    @empty
                    <span class="text-body-secondary small">{{ __('Aucune permission directe') }}</span>
                    @endforelse
                </div>

                @else
                <div class="alert alert-info border-0 rounded-3 mb-0 d-flex gap-3 align-items-start">
                    <i class="bi bi-info-circle-fill fs-5 mt-1 flex-shrink-0"></i>
                    <div>
                        <h6 class="mb-1">{{ __('Aucun compte associé') }}</h6>
                        <p class="mb-0 small">
                            {{ __("Cette personne n'a pas encore de compte utilisateur.") }}
                        </p>
                    </div>
                </div>
                @endif
            </div>
        </div>

    </div>
</x-admin-layout>