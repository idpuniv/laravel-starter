<div class="table-responsive">
    <table class="table table-hover table-bordered">

        <thead class="table-light">
            <tr>
                <th>#</th>
                <th>{{ __('Nom') }}</th>
                <th>{{ __('Prénom') }}</th>
                <th>{{ __('Téléphone') }}</th>
                <th>{{ __('Date') }}</th>
                <th>{{ __('Rôle') }}</th>
                <th>{{ __('Statut') }}</th>
                <th>{{ __('Actions') }}</th>
            </tr>
        </thead>

        <tbody>
            @forelse ($data as $person)
            <tr>
                <td>{{ $loop->iteration }}</td>

                <td>{{ $person->last_name }}</td>

                <td>{{ $person->first_name }}</td>

                <td>{{ $person->full_phone ?: '—' }}</td>

                <td>{{ $person->created_at->format('d/m/Y H:i') }}</td>

                {{-- RÔLE --}}
                <td>
                    @if(!$person->user)
                        <span class="badge bg-secondary">{{ __('Pas de compte') }}</span>
                    @else
                        @php
                            $roles = $person->user->getRoleNames();
                            $count = $roles->count();
                        @endphp

                        @if ($count === 0)
                            <span class="text-muted fst-italic">{{ __('Aucun rôle') }}</span>
                        @else
                            @foreach ($roles->take(2) as $roleName)
                                <span class="badge bg-secondary me-1 mb-1">
                                    {{ __($roleName) }}
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

                {{-- STATUT --}}
                <td>
                    @if($person->user)
                        @can(\App\Permissions\UserPermissions::UPDATE)
                            <form method="POST" action="{{ route('admin.users.change-status', $person->user) }}" class="d-inline">
                                @csrf
                                @method('PATCH')

                                <select name="status"
                                        class="form-select form-select-sm @error('status') is-invalid @enderror"
                                        onchange="this.form.submit()"
                                        style="width: auto; min-width: 120px;">
                                    <option value="{{ \App\Enums\UserStatus::ACTIVE->value }}"
                                        @selected($person->user->status === \App\Enums\UserStatus::ACTIVE)>
                                        <i class="{{ \App\Support\StatusUI::icon(\App\Enums\UserStatus::ACTIVE) }} me-1"></i>
                                        {{ \App\Enums\UserStatus::ACTIVE->label() }}
                                    </option>

                                    <option value="{{ \App\Enums\UserStatus::INACTIVE->value }}"
                                        @selected($person->user->status === \App\Enums\UserStatus::INACTIVE)>
                                        <i class="{{ \App\Support\StatusUI::icon(\App\Enums\UserStatus::INACTIVE) }} me-1"></i>
                                        {{ \App\Enums\UserStatus::INACTIVE->label() }}
                                    </option>

                                    <option value="{{ \App\Enums\UserStatus::BANNED->value }}"
                                        @selected($person->user->status === \App\Enums\UserStatus::BANNED)>
                                        <i class="{{ \App\Support\StatusUI::icon(\App\Enums\UserStatus::BANNED) }} me-1"></i>
                                        {{ \App\Enums\UserStatus::BANNED->label() }}
                                    </option>
                                </select>

                                @error('status')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </form>
                        @else
                            <span class="badge {{ match($person->user->status) {
                                \App\Enums\UserStatus::ACTIVE => 'bg-success',
                                \App\Enums\UserStatus::INACTIVE => 'bg-warning',
                                \App\Enums\UserStatus::BANNED => 'bg-danger',
                            } }}">
                                <i class="{{ \App\Support\StatusUI::icon($person->user->status) }} me-1"></i>
                                {{ $person->user->status->label() }}
                            </span>
                        @endcan
                    @else
                        <span class="badge bg-secondary">{{ __('N/A') }}</span>
                    @endif
                </td>

                {{-- ACTIONS --}}
                <td>
                    <div class="d-flex align-items-center gap-2">
                        @can(\App\Permissions\UserPermissions::VIEW)
                            <a href="{{ route('admin.users.show', $person->id) }}"
                               class="icon-circle-xs text-decoration-none text-body hover-bg-secondary-25"
                               title="{{ __('Voir') }}">
                                <i class="bi bi-eye"></i>
                                <span class="visually-hidden">{{ __('Voir') }}</span>
                            </a>
                        @endcan

                        @can(\App\Permissions\UserPermissions::UPDATE)
                            <a href="{{ route('admin.users.edit', $person->id) }}"
                               class="icon-circle-xs text-decoration-none text-body hover-bg-secondary-25"
                               title="{{ __('Modifier') }}">
                                <i class="bi bi-pencil"></i>
                                <span class="visually-hidden">{{ __('Modifier') }}</span>
                            </a>
                        @endcan

                        @can(\App\Permissions\UserPermissions::DELETE)
                            <button type="button"
                                    class="btn btn-link text-danger icon-circle-xs text-decoration-none p-0 m-0"
                                    data-bs-toggle="modal"
                                    data-bs-target="#confirmModal"
                                    data-url="{{ route('admin.users.destroy', $person->id) }}"
                                    data-method="DELETE"
                                    title="{{ __('Supprimer') }}">
                                <i class="bi bi-trash"></i>
                                <span class="visually-hidden">{{ __('Supprimer') }}</span>
                            </button>
                        @endcan
                    </div>
                </td>
            </tr>
            @empty
                <tr>
                    <td colspan="8" class="text-center py-4 text-muted">
                        {{ __('Aucune personne trouvée.') }}
                    </td>
                </tr>
            @endforelse
        </tbody>

    </table>
</div>

@include('partials.pagination', ['data' => $data])