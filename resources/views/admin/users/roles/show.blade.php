<x-admin-layout>
    <div class="container">

        <h1>Rôles de {{ $user->name }}</h1>

        <div class="card">
            <div class="card-body">

                <h5>Rôles attribués</h5>

                @forelse($user->roles as $role)
                    <span class="badge bg-primary">{{ $role->name }}</span>
                @empty
                    <p>Aucun rôle attribué</p>
                @endforelse

                <hr>

                <a href="{{ route('admin.users.roles.edit', $user->id) }}"
                   class="btn btn-warning">
                    Modifier les rôles
                </a>

                <a href="{{ route('admin.users.index') }}"
                   class="btn btn-secondary">
                    Retour
                </a>

            </div>
        </div>

    </div>
</x-admin-layout>