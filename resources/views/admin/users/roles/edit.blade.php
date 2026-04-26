<x-admin-layout>
    <div class="container">
        <h1>Détails utilisateur</h1>

        <div class="card">
            <div class="card-body">

                <h4>{{ $user->name }}</h4>
                <p>{{ $user->email }}</p>

                <hr>

                {{-- ===================== ROLES INLINE ===================== --}}
                <h5>Rôles</h5>

                <form method="POST" action="{{ route('admin.users.roles.update', $user->id) }}">
                    @csrf
                    @method('PUT')

                    <div class="mb-2">
                        @foreach($roles as $role)
                            <div class="form-check">
                                <input type="checkbox"
                                       name="roles[]"
                                       value="{{ $role->name }}"
                                       class="form-check-input"
                                       {{ $user->hasRole($role->name) ? 'checked' : '' }}>

                                <label class="form-check-label">
                                    {{ $role->name }}
                                </label>
                            </div>
                        @endforeach
                    </div>

                    <button class="btn btn-sm btn-primary">
                        Sauvegarder rôles
                    </button>
                </form>

                <hr>

                {{-- ===================== PERMISSIONS LINK ===================== --}}
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h5 class="mb-0">Permissions</h5>

                    <a href="{{ route('admin.users.permissions.edit', $user->id) }}"
                       class="btn btn-sm btn-info">
                        Gérer permissions
                    </a>
                </div>

                @forelse($user->permissions as $permission)
                    <span class="badge bg-secondary">{{ $permission->name }}</span>
                @empty
                    <p>Aucune permission attribuée</p>
                @endforelse

            </div>
        </div>
    </div>
</x-admin-layout>