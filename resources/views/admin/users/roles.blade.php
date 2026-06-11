@section('title', 'Modifier les rôles de ' . $user->name)

<x-admin-layout>
<div class="container py-4">

    <h3>Détails utilisateur</h3>

    <div class="card">
        <div class="card-body">

            <h4 class="mb-1">{{ $user->name }}</h4>
            <p class="text-muted">{{ $user->email }}</p>

            <hr>

            {{-- ROLES --}}
            <h5>Rôles</h5>

            <form method="POST" action="{{ route('admin.users.roles.update', $user->id) }}">
                @csrf
                @method('PUT')

                <div class="mb-3">

                    @foreach($roles as $role)
                        <div class="form-check">
                            <input type="checkbox"
                                   name="roles[]"
                                   value="{{ $role->name }}"
                                   class="form-check-input"
                                   id="role_{{ $role->id }}"
                                   {{ $user->hasRole($role->name) ? 'checked' : '' }}>

                            <label class="form-check-label" for="role_{{ $role->id }}">
                                {{ $role->label }}
                            </label>
                        </div>
                    @endforeach

                </div>

                <button class="btn btn-primary btn-sm">
                    Sauvegarder
                </button>
            </form>

            <hr>

            {{-- PERMISSIONS --}}
            <div class="d-flex justify-content-between align-items-center mb-2">
                <h5 class="mb-0">Permissions</h5>

                <a href="{{ route('admin.users.permissions.edit', $user->id) }}"
                   class="btn btn-sm btn-outline-primary">
                    Gérer
                </a>
            </div>

            <div class="d-flex flex-wrap gap-1">

                @forelse($user->permissions as $permission)
                    <span class="badge bg-secondary">
                        {{ $permission->label }}
                    </span>
                @empty
                    <span class="text-muted">
                        Aucune permission attribuée
                    </span>
                @endforelse

            </div>

        </div>
    </div>

</div>
</x-admin-layout>