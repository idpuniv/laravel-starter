<x-admin-layout>
    <div class="container">
        <h1>Permissions de {{ $user->name }}</h1>

        <form method="POST" action="{{ route('admin.users.permissions.update', $user->id) }}">
            @csrf
            @method('PUT')

            <div class="card">
                <div class="card-body">

                    {{-- Permissions via rôles --}}
                    <h6 class="mb-3">Permissions via rôles (non modifiables)</h6>

                    @forelse($rolePermissions as $perm)
                        <span class="badge bg-secondary mb-1">{{ $perm }}</span>
                    @empty
                        <p class="text-muted">Aucune permission via rôles</p>
                    @endforelse

                    <hr>

                    {{-- Permissions directes --}}
                    <h6 class="mb-3">Permissions directes</h6>

                    @if($permissions->isNotEmpty())

                        @foreach($permissions as $permission)
                            <div class="form-check">
                                <input type="checkbox"
                                       name="permissions[]"
                                       value="{{ $permission->name }}"
                                       class="form-check-input"
                                       {{ $user->hasPermissionTo($permission->name) ? 'checked' : '' }}>

                                <label class="form-check-label">
                                    {{ $permission->name }}
                                </label>
                            </div>
                        @endforeach

                    @else
                        <p class="text-muted">
                            Aucune permission supplémentaire disponible
                        </p>
                    @endif

                </div>
            </div>

            @if($permissions->isNotEmpty())
                <button class="btn btn-primary mt-3">
                    Enregistrer
                </button>
            @endif

        </form>
    </div>
</x-admin-layout>