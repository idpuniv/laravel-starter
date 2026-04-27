@section('title', $user->name)
<x-admin-layout>
<div class="container py-4">

    <h3>Détails utilisateur</h3>

    <div class="card">
        <div class="card-body">

            <div class="row g-3">

                <div class="col-md-6">
                    <strong>Nom</strong>
                    <div>{{ $user->name }}</div>
                </div>

                <div class="col-md-6">
                    <strong>Username</strong>
                    <div>{{ $user->username ?? '-' }}</div>
                </div>

                <div class="col-md-6">
                    <strong>Email</strong>
                    <div>{{ $user->email }}</div>
                </div>

                <div class="col-md-6">
                    <strong>Statut</strong>
                    <div>
                        @if($user->status === 'active')
                            <span class="badge bg-success">Actif</span>
                        @else
                            <span class="badge bg-secondary">Inactif</span>
                        @endif
                    </div>
                </div>

                <div class="col-md-6">
                    <strong>Équipe</strong>
                    <div>
                        {{ $user->team->name ?? 'Aucune équipe' }}
                    </div>
                </div>

            </div>

            <hr>

            <div class="d-flex justify-content-between align-items-center mb-2">
                <h5 class="mb-0">Rôles</h5>

                <a href="{{ route('admin.users.roles.edit', $user->id) }}"
                   class="btn btn-sm btn-warning">
                    Modifier
                </a>
            </div>

            <div class="mb-3">
                @forelse($user->roles as $role)
                    <span class="badge bg-primary">{{ $role->name }}</span>
                @empty
                    <span class="text-muted">Aucun rôle</span>
                @endforelse
            </div>

            <hr>

            <div class="d-flex justify-content-between align-items-center mb-2">
                <h5 class="mb-0">Permissions</h5>

                <a href="{{ route('admin.users.permissions.edit', $user->id) }}"
                   class="btn btn-sm btn-info">
                    Modifier
                </a>
            </div>

            <div class="mb-3">
                @forelse($user->permissions as $permission)
                    <span class="badge bg-secondary">{{ $permission->name }}</span>
                @empty
                    <span class="text-muted">Aucune permission</span>
                @endforelse
            </div>

            <div class="mt-4 d-flex justify-content-end gap-2">

                <a href="{{ route('admin.users.edit', $user->id) }}"
                   class="btn btn-primary">
                    Modifier utilisateur
                </a>

            </div>

        </div>
    </div>

</div>
</x-admin-layout>