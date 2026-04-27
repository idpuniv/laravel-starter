<div class="table-responsive">
    <table class="table table-hover table-bordered">

        <thead class="table-light">
            <tr>
                <th>#</th>
                <th>Nom d'utilisateur</th>
                <th>Email</th>
                <th>Date</th>
                <th>Rôle</th>
            </tr>
        </thead>

        <tbody>
            @forelse ($data as $user)
                <tr>

                    <td>{{ $loop->iteration }}</td>

                    <td>
                        <a href="{{ route('admin.users.show', $user->id) }}">
                            {{ $user->username }}
                        </a>
                    </td>

                    <td>
                        <a href="{{ route('admin.users.show', $user->id) }}">
                            {{ $user->email }}
                        </a>
                    </td>

                    <td>
                        {{ $user->created_at->format('d/m/Y H:i') }}
                    </td>

                    <td>
                        @php
                            $roles = $user->getRoleNames();
                            $count = $roles->count();
                        @endphp

                        <a href="{{ route('admin.users.show', $user->id) }}">

                            @if ($count === 0)
                                <span class="text-muted small">Aucun rôle</span>
                            @else
                                @foreach ($roles->take(2) as $roleName)
                                    <span class="badge bg-light text-dark border">
                                        {{ $roleName }}
                                    </span>
                                @endforeach

                                @if ($count > 2)
                                    <span class="badge bg-secondary">
                                        +{{ $count - 2 }}
                                    </span>
                                @endif
                            @endif

                        </a>
                    </td>

                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center py-4 text-muted">
                        Aucun utilisateur trouvé.
                    </td>
                </tr>
            @endforelse
        </tbody>

    </table>

    @include('partials.pagination', ['data' => $data])
</div>