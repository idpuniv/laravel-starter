
<x-admin-layout>
<div class="container py-4">

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="card">
        <div class="card-body p-0">
            <table class="table table-hover mb-0 align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Module</th>
                        <th>Nom (code)</th>
                        <th>Label</th>
                        <th width="200">Actions</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($permissions as $permission)
                        <tr>
                            <td>{{ $permission->id }}</td>

                            <td>
                                @if($permission->module)
                                    <span class="badge bg-info">
                                        {{ $permission->module->label ?? $permission->module->name }}
                                    </span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>

                            <td>
                                <code>{{ $permission->name }}</code>
                            </td>

                            <td>
                                {{ $permission->label ?? $permission->name }}
                            </td>

                            <td>
                                <a href="{{ route('admin.permissions.show', $permission->id) }}" class="btn btn-sm btn-info">
                                    Voir
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center">Aucune permission</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-3">
        {{ $permissions->links() }}
    </div>

</div>
</x-admin-layout>