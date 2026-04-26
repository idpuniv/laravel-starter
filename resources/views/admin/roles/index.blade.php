{{-- resources/views/admin/roles/index.blade.php --}}

<x-admin-layout>
<div class="container py-4">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3>Liste des rôles</h3>
        <a href="{{ route('admin.roles.create') }}" class="btn btn-primary">
            + Nouveau rôle
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="card">
        <div class="card-body p-0">
            <table class="table table-hover mb-0">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Nom</th>
                        <th>Permissions</th>
                        <th width="200">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($roles as $role)
                        <tr>
                            <td>{{ $role->id }}</td>
                            <td>{{ $role->name }}</td>
                            <td>
                                @foreach($role->permissions as $permission)
                                    <span class="badge bg-secondary">
                                        {{ $permission->name }}
                                    </span>
                                @endforeach
                            </td>
                            <td>
                                <a href="{{ route('admin.roles.show', $role->id) }}" class="btn btn-sm btn-info">Voir</a>
                                <a href="{{ route('admin.roles.edit', $role->id) }}" class="btn btn-sm btn-warning">Modifier</a>

                                <form action="{{ route('admin.roles.destroy', $role->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-danger" onclick="return confirm('Supprimer ?')">
                                        Supprimer
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center">Aucun rôle trouvé</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-3">
        {{ $roles->links() }}
    </div>

</div>
</x-admin-layout>