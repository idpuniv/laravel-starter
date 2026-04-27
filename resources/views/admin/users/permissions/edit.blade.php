@section('title', 'Modifier les permissions de ' . $user->name)

<x-admin-layout>
<div class="container py-4">

    <h3>Permissions de {{ $user->name }}</h3>

    <form method="POST" action="{{ route('admin.users.permissions.update', $user->id) }}">
        @csrf
        @method('PUT')

        <div class="card">
            <div class="card-body">

                {{-- PERMISSIONS VIA ROLES --}}
                <h6 class="mb-3">Permissions via rôles (non modifiables)</h6>

                <div class="d-flex flex-wrap gap-1 mb-3">

                    @forelse($rolePermissions as $perm)
                        <span class="badge bg-secondary">
                            {{ $perm }}
                        </span>
                    @empty
                        <span class="text-muted">
                            Aucune permission via rôles
                        </span>
                    @endforelse

                </div>

                <hr>

                {{-- PERMISSIONS DIRECTES --}}
                <h6 class="mb-3">Permissions directes</h6>

                @if($permissions->isNotEmpty())

                    <div class="row g-2">

                        @foreach($permissions as $permission)
                            <div class="col-md-4">

                                <div class="form-check">
                                    <input type="checkbox"
                                           name="permissions[]"
                                           value="{{ $permission->name }}"
                                           id="perm_{{ $permission->id }}"
                                           class="form-check-input"
                                           {{ $user->hasPermissionTo($permission->name) ? 'checked' : '' }}>

                                    <label class="form-check-label" for="perm_{{ $permission->id }}">
                                        {{ $permission->name }}
                                    </label>
                                </div>

                            </div>
                        @endforeach

                    </div>

                @else
                    <span class="text-muted">
                        Aucune permission supplémentaire disponible
                    </span>
                @endif

            </div>
        </div>

        @if($permissions->isNotEmpty())
            <div class="mt-3 d-flex justify-content-end">
                <button class="btn btn-primary">
                    Enregistrer
                </button>
            </div>
        @endif

    </form>

</div>
</x-admin-layout>