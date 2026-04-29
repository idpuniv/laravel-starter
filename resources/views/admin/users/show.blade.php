@section('title', $person->first_name . ' ' . $person->last_name)
<x-admin-layout>
<div class="container py-4">

    <div class="card">
        <div class="card-body p-4">

            {{-- En-tête avec icône centrée --}}
            <div class="text-center mb-4">
                <div class="avatar mb-3">
                    <i class="bi bi-person"></i>
                </div>
                <h4 class="mb-1">{{ $person->first_name }} {{ $person->last_name }}</h4>
                <small class="text-muted">Créé le {{ $person->created_at->format('d/m/Y') }}</small>
            </div>

            <hr>

            {{-- Informations --}}
            <div class="row g-3">
                <div class="col-md-6">
                    <small class="text-muted d-block">Prénom</small>
                    <strong>{{ $person->first_name }}</strong>
                </div>
                <div class="col-md-6">
                    <small class="text-muted d-block">Nom</small>
                    <strong>{{ $person->last_name }}</strong>
                </div>
                <div class="col-md-6">
                    <small class="text-muted d-block">Téléphone</small>
                    <strong>{{ $person->full_phone ?: '-' }}</strong>
                </div>
                <div class="col-md-6">
                    <small class="text-muted d-block">Genre</small>
                    <strong>
                        @if($person->gender === 'male') Masculin
                        @elseif($person->gender === 'female') Féminin
                        @else -
                        @endif
                    </strong>
                </div>
                <div class="col-md-6">
                    <small class="text-muted d-block">Pays</small>
                    <strong>{{ $person->country->name ?? '-' }}</strong>
                </div>
                <div class="col-md-6">
                    <small class="text-muted d-block">Date création</small>
                    <strong>{{ $person->created_at->format('d/m/Y H:i') }}</strong>
                </div>
            </div>

            <hr>

            {{-- Compte utilisateur --}}
            <div class="d-flex justify-content-between align-items-center mb-3">
                <strong>Compte utilisateur</strong>
                @if(!$person->user)
                    <a href="{{ route('admin.users.create', ['person_id' => $person->id]) }}" class="btn btn-sm btn-primary">
                        <i class="bi bi-plus"></i> Créer
                    </a>
                @endif
            </div>

            @if($person->user)
                <div class="row g-3">
                    <div class="col-md-6">
                        <small class="text-muted d-block">Nom d'utilisateur</small>
                        <strong>{{ $person->user->username ?? '-' }}</strong>
                    </div>
                    <div class="col-md-6">
                        <small class="text-muted d-block">Email</small>
                        <strong>{{ $person->user->email }}</strong>
                    </div>
                    <div class="col-md-6">
                        <small class="text-muted d-block">Statut</small>
                        <strong>
                            @if($person->user->status === 'active')
                                <span class="badge bg-success">Actif</span>
                            @elseif($person->user->status === 'inactive')
                                <span class="badge bg-secondary">Inactif</span>
                            @elseif($person->user->status === 'banned')
                                <span class="badge bg-danger">Banni</span>
                            @endif
                        </strong>
                    </div>
                    <div class="col-md-6">
                        <small class="text-muted d-block">Équipe</small>
                        <strong>{{ $person->user->team->name ?? '-' }}</strong>
                    </div>
                </div>

                <div class="mt-3">
                    <small class="text-muted d-block mb-2">Rôles</small>
                    <div>
                        @forelse($person->user->roles as $role)
                            <span class="badge bg-primary me-1">{{ $role->name }}</span>
                        @empty
                            <span class="text-muted">Aucun</span>
                        @endforelse
                        <a href="{{ route('admin.users.roles.edit', $person->user->id) }}" class="ms-2">
                            <i class="bi bi-pencil"></i>
                        </a>
                    </div>
                </div>

                <div class="mt-3">
                    <small class="text-muted d-block mb-2">Permissions directes</small>
                    <div>
                        @forelse($person->user->permissions as $permission)
                            <span class="badge bg-secondary me-1">{{ $permission->name }}</span>
                        @empty
                            <span class="text-muted">Aucune</span>
                        @endforelse
                        <a href="{{ route('admin.users.permissions.edit', $person->user->id) }}" class="ms-2">
                            <i class="bi bi-pencil"></i>
                        </a>
                    </div>
                </div>
            @else
                <div class="alert alert-info mb-0">
                    <i class="bi bi-info-circle"></i> Aucun compte associé
                </div>
            @endif

            <hr>

            <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('admin.users.index') }}" class="btn btn-secondary btn-sm">Retour</a>
                @if($person->user)
                    <a href="{{ route('admin.users.edit', $person->id) }}" class="btn btn-primary btn-sm">Modifier</a>
                @endif
            </div>

        </div>
    </div>

</div>
</x-admin-layout>