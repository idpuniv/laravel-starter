@section('title', 'Modifier une personne')

<x-admin-layout>

<div class="container py-4">

    {{-- Carte informations de la personne --}}
    <div class="card shadow-sm border-0 rounded-4 mb-4">
        <div class="card-header bg-transparent border-bottom-0 pt-4 px-4">
            <div class="d-flex align-items-center gap-2">
                <i class="bi bi-person-circle text-primary fs-4"></i>
                <h5 class="mb-0">Informations personnelles</h5>
            </div>
        </div>
        <div class="card-body p-4 pt-0">
            <form method="POST" action="{{ route('admin.people.update', $person->id) }}">
                @csrf
                @method('PUT')

                @include('admin.people.partials.form')

                <div class="mt-4 d-flex justify-content-end gap-2">
                    <button type="submit" class="btn btn-primary">
                     Mettre à jour
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Carte compte utilisateur --}}
    @if($person->user)
    <div class="card shadow-sm border-0 rounded-4 mb-4">
        <div class="card-header bg-transparent border-bottom-0 pt-4 px-4">
            <div class="d-flex align-items-center gap-2">
                <i class="bi bi-person-check text-primary fs-4"></i>
                <h5 class="mb-0">Compte utilisateur</h5>
            </div>
        </div>
        <div class="card-body p-4 pt-0">
            <form method="POST" action="{{ route('admin.users.update', $person->user->id) }}">
                @csrf
                @method('PUT')
                
                @include('admin.users.partials.form', ['user' => $person->user])
                
                <div class="mt-4 d-flex justify-content-end gap-2">
                    <button type="submit" class="btn btn-primary">
                     Mettre à jour le compte
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Carte rôles et permissions --}}
    <div class="card shadow-sm border-0 rounded-4">
        <div class="card-header bg-transparent border-bottom-0 pt-4 px-4">
            <div class="d-flex align-items-center gap-2">
                <i class="bi bi-shield-lock text-primary fs-4"></i>
                <h5 class="mb-0">Rôles et permissions</h5>
            </div>
        </div>
        <div class="card-body p-4 pt-0">
            {{-- Rôles --}}
            <div class="mb-4">
                <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
                    <div class="d-flex align-items-center gap-2">
                        <i class="bi bi-shield-check text-primary"></i>
                        <strong class="mb-0">Rôles</strong>
                    </div>
                    @can(\App\Permissions\UserPermissions::UPDATE_ROLE)
                        <a href="{{ route('admin.users.roles.edit', $person->user->id) }}" class="btn btn-sm btn-outline-primary">
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
            <div class="pt-3 border-top">
                <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
                    <div class="d-flex align-items-center gap-2">
                        <i class="bi bi-key text-primary"></i>
                        <strong class="mb-0">Permissions directes</strong>
                    </div>
                    @can(\App\Permissions\UserPermissions::UPDATE_PERMISSION)
                        <a href="{{ route('admin.users.permissions.edit', $person->user->id) }}" class="btn btn-sm btn-outline-primary">
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
        </div>
    </div>
    @else
    <div class="card shadow-sm border-0 rounded-4">
        <div class="card-body p-4 text-center">
            <i class="bi bi-info-circle text-muted fs-1"></i>
            <p class="mt-2 mb-0">Aucun compte utilisateur associé</p>
            <a href="{{ route('admin.people.show-add-user-form', $person->id) }}" class="btn btn-sm btn-primary mt-3">
                <i class="bi bi-plus-lg"></i> Créer un compte
            </a>
        </div>
    </div>
    @endif

</div>
</x-admin-layout>