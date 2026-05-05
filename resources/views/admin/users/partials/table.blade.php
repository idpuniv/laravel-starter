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
                <th>Actions</th>
            </tr>
        </thead>

        <tbody>
            @forelse ($data as $person)
                <tr>
                    <td>{{ $loop->iteration }}</td>

                    <td>{{ $person->last_name }}</td>

                    <td>{{ $person->first_name }}</td>

                    <td>{{ $person->full_phone }}</td>

                    <td>{{ $person->created_at->format('d/m/Y H:i') }}</td>

                    <td>
                        @if(!$person->user)
                            <span class="badge bg-secondary">Pas de compte</span>
                        @else
                            @php
                                $roles = $person->user->getRoleNames();
                                $count = $roles->count();
                            @endphp

                            @if ($count === 0)
                                <span class="text-muted fst-italic">Aucun rôle</span>
                            @else
                                @foreach ($roles->take(2) as $roleName)
                                    <span class="badge bg-secondary me-1 mb-1">
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
                    </td>

                    <td>
                        <div class="d-flex align-items-center">
                            @can(\App\Permissions\UserPermissions::VIEW)
                                <a href="{{ route('admin.users.show', $person->id) }}"
                                    class="icon-circle-xs text-decoration-none text-body hover-bg-secondary-25"
                                    title="Voir">
                                    <i class="bi bi-eye"></i>
                                    <span class="visually-hidden">Voir</span>
                                </a>
                            @endcan

                            @can(\App\Permissions\UserPermissions::UPDATE)
                                <a href="{{ route('admin.users.edit', $person->id) }}"
                                    class="icon-circle-xs text-decoration-none text-body hover-bg-secondary-25"
                                    title="Modifier">
                                    <i class="bi bi-pencil"></i>
                                    <span class="visually-hidden">Modifier</span>
                                </a>
                            @endcan

                            @can(\App\Permissions\UserPermissions::DELETE)
                                <form action="{{ route('admin.users.destroy', $person->id) }}" method="POST"
                                    class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="icon-circle-xs text-decoration-none text-body bg-transparent border-0 hover-bg-secondary-25"
                                        title="Supprimer">
                                        <i class="bi bi-trash"></i>
                                        <span class="visually-hidden">Supprimer</span>
                                    </button>
                                </form>
                            @endcan
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center py-4 text-muted">
                        Aucune personne trouvée.
                    </td>
                </tr>
            @endforelse
        </tbody>

    </table>

</div>
@include('partials.pagination', ['data' => $data])