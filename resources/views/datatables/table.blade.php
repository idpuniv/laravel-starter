<table class="table table-hover">
    <thead>
    <tr>
        <th class="sortable" data-field="id">#</th>
        <th class="sortable" data-field="username">Username</th>
        <th class="sortable" data-field="email">Email</th>
        <th class="sortable" data-field="created_at">Date</th>
        <th>Role</th>
    </tr>
</thead>
    <tbody>
        @forelse ($data as $user)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $user->username }}</td>
                <td>{{ $user->email }}</td>
                <td>
                    {{ $user->created_at }}
                </td>
                <td>
                    @if ($user->roles->isNotEmpty())
                        @foreach ($user->getRoleNames() as $roleName)
                            <span class="badge bg-light text-dark border">{{ $roleName }}</span>
                        @endforeach
                    @else
                        <span class="text-muted small">Aucun rôle</span>
                    @endif  
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="3" class="text-center py-4">Aucun utilisateur trouvé.</td>
            </tr>
        @endforelse
    </tbody>
</table>

@include('partials.pagination', ['data' => $data])
