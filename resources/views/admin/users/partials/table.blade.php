<div class="table-responsive">
    <table class="table table-hover table-bordered">

        <thead class="table-light">
            <tr>
                <th>#</th>
                <th>Nom</th>
                <th>Prénom</th>
                <th>Téléphone</th>
                <th>Date</th>
                <th>Rôle</th>
            </tr>
        </thead>

        <tbody>
            @forelse ($data as $person)
                <tr>

                    <td>{{ $loop->iteration }}</td>

                    <td>
                        <a href="{{ route('admin.users.show', $person->id) }}">
                            {{ $person->last_name }}
                        </a>
                    </td>

                    <td>
                        <a href="{{ route('admin.users.show', $person->id) }}">
                            {{ $person->first_name }}
                        </a>
                    </td>

                    <td>
                        <a href="{{ route('admin.users.show', $person->id) }}">
                            {{ $person->full_phone }}
                        </a>
                    </td>

                    <td>
                        {{ $person->created_at->format('d/m/Y H:i') }}
                    </td>

                    <td>
                        <a href="{{ route('admin.users.show', $person->id) }}">
                            @if(!$person->user)
                                <span class="badge bg-secondary">Pas de compte</span>
                            @else
                                @php
                                    $roles = $person->user->getRoleNames();
                                    $count = $roles->count();
                                @endphp

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
                            @endif
                        </a>
                    </td>

                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center py-4 text-muted">
                        Aucune personne trouvée.
                    </td>
                </tr>
            @endforelse
        </tbody>

    </table>

    @include('partials.pagination', ['data' => $data])
</div>