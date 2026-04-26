<div class="table-responsive">
    <table class="table table-hover table-bordered">
    <thead class="table-light">
        <tr>
            <th class="sortable" data-field="id">#</th>
            <th class="sortable" data-field="username">Nom d'utilisateur</th>
            <th class="sortable" data-field="email">Email</th>
            <th class="sortable" data-field="created_at">Date</th>
            <th>Rôle</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($data as $user)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $user->username }}</td>
                <td>{{ $user->email }}</td>
                <td>{{ $user->created_at->format('d/m/Y H:i') }}</td>
                <td>
    @php
        $roles = $user->getRoleNames();
        $count = $roles->count();
    @endphp

    <a href="{{ route('admin.users.roles.show', $user->id) }}" class="text-decoration-none">
        @if ($count === 0)
            <span class="text-muted small">Aucun rôle</span>
        @else
            @foreach ($roles->take(2) as $roleName)
                <span class="badge bg-light text-dark border">{{ $roleName }}</span>
            @endforeach

            @if ($count > 2)
                <span class="badge bg-secondary">+{{ $count - 2 }}</span>
            @endif
        @endif
    </a>
</td>
            </tr>
        @empty
            <tr>
                <td colspan="5" class="text-center py-4 text-muted">
                    <i class="bi bi-people fs-3 d-block mb-2"></i>
                    Aucun utilisateur trouvé.
                </td>
            </tr>
        @endforelse
    </tbody>
</table>

@include('partials.pagination', ['data' => $data])
</div>