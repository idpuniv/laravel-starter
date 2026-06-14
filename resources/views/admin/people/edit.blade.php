@section('title', __('Modifier - ') . $person->fullName)

<x-admin-layout>
    <div class="container py-4">

        {{-- Header --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <x-breadcrumb></x-breadcrumb>
            <div class="d-flex align-items-center gap-2">
                @can(\App\Permissions\PersonPermissions::DELETE)
                <button type="button"
                    class="btn btn-link text-danger icon-circle-xs text-decoration-none hover-bg-secondary-25 p-0 m-0"
                    data-bs-toggle="modal"
                    data-bs-target="#confirmModal{{ $person->id }}"
                    title="{{ __('Supprimer') }}">
                    <i class="bi bi-trash"></i>
                </button>
                @endcan
            </div>
        </div>

        {{-- Personal info --}}
        <div class="card border-0 shadow-sm rounded-4 mb-4">
            <div class="card-header bg-transparent border-bottom px-4 pt-4 pb-3">
                <h6 class="mb-0 fw-semibold d-flex align-items-center gap-2">
                    <i class="bi bi-person-lines-fill text-primary"></i>
                    {{ __('Informations personnelles') }}
                </h6>
            </div>
            <div class="card-body p-4">
                <form method="POST" action="{{ route('admin.people.update', $person->id) }}">
                    @csrf
                    @method('PUT')

                    @include('admin.people.partials.form')

                    <div class="mt-4 d-flex justify-content-end">
                        <button type="submit" class="btn btn-sm btn-primary">
                            <i class="bi bi-check-lg me-1"></i>{{ __('Mettre à jour') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>

        @if($person->user)

        {{-- User account --}}
        <div class="card border-0 shadow-sm rounded-4 mb-4">
            <div class="card-header bg-transparent border-bottom px-4 pt-4 pb-3">
                <h6 class="mb-0 fw-semibold d-flex align-items-center gap-2">
                    <i class="bi bi-person-check text-primary"></i>
                    {{ __('Compte utilisateur') }}
                </h6>
            </div>
            <div class="card-body p-4">
                <form method="POST" action="{{ route('admin.users.update', $person->user->id) }}">
                    @csrf
                    @method('PUT')

                    @include('admin.users.partials.form', ['user' => $person->user])

                    <div class="mt-4 d-flex justify-content-end">
                        <button type="submit" class="btn btn-sm btn-primary">
                            <i class="bi bi-check-lg me-1"></i>{{ __('Mettre à jour le compte') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Roles & permissions --}}
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-header bg-transparent border-bottom px-4 pt-4 pb-3">
                <h6 class="mb-0 fw-semibold d-flex align-items-center gap-2">
                    <i class="bi bi-shield-lock text-primary"></i>
                    {{ __('Rôles et permissions') }}
                </h6>
            </div>
            <div class="card-body p-4">

                {{-- Roles --}}
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
                    <p class="fw-semibold mb-0 d-flex align-items-center gap-2">
                        <i class="bi bi-shield-check text-primary"></i>{{ __('Rôles') }}
                    </p>
                    @can(\App\Permissions\UserPermissions::UPDATE_ROLE)
                    <a href="{{ route('admin.users.roles.edit', $person->user->id) }}"
                        class="btn btn-link text-body icon-circle-xs text-decoration-none hover-bg-secondary-25 p-0 m-0"
                        title="{{ __('Gérer les rôles') }}">
                        <i class="bi bi-pencil"></i>
                    </a>
                    @endcan
                </div>
                <div class="d-flex flex-wrap gap-2">
                    @forelse($person->user->roles as $role)
                    <span class="badge rounded-pill border border-primary-subtle text-primary bg-primary-subtle px-3 py-2">
                        <i class="bi bi-shield-check me-1"></i>{{ $role->label }}
                    </span>
                    @empty
                    <span class="text-body-secondary small">{{ __('Aucun rôle assigné') }}</span>
                    @endforelse
                </div>

                <hr class="my-4">

                {{-- Direct permissions --}}
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
                    <p class="fw-semibold mb-0 d-flex align-items-center gap-2">
                        <i class="bi bi-key text-primary"></i>{{ __('Permissions directes') }}
                    </p>
                    @can(\App\Permissions\UserPermissions::UPDATE_PERMISSION)
                    <a href="{{ route('admin.users.permissions.edit', $person->user->id) }}"
                        class="btn btn-link text-body icon-circle-xs text-decoration-none hover-bg-secondary-25 p-0 m-0"
                        title="{{ __('Gérer les permissions') }}">
                        <i class="bi bi-pencil"></i>
                    </a>
                    @endcan
                </div>
                <div class="d-flex flex-wrap gap-2">
                    @forelse($person->user->permissions as $permission)
                    <span class="badge rounded-pill border border-secondary-subtle text-secondary bg-secondary-subtle px-3 py-2">
                        <i class="bi bi-check-lg me-1"></i>{{ $permission->label }}
                    </span>
                    @empty
                    <span class="text-body-secondary small">{{ __('Aucune permission directe') }}</span>
                    @endforelse
                </div>

            </div>
        </div>

        @else

        {{-- No account --}}
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body p-5 text-center">
                <div class="rounded-circle bg-body-tertiary d-inline-flex align-items-center justify-content-center mb-3"
                    style="width:56px;height:56px;font-size:1.5rem;">
                    <i class="bi bi-person-x text-body-secondary"></i>
                </div>
                <p class="text-body-secondary mb-3 small">
                    {{ __('Aucun compte utilisateur associé à cette personne.') }}
                </p>
                @can(\App\Permissions\UserPermissions::CREATE)
                <a href="{{ route('admin.people.show-add-user-form', $person->id) }}"
                    class="btn btn-sm btn-primary">
                    <i class="bi bi-plus-lg me-1"></i>{{ __('Créer un compte') }}
                </a>
                @endcan
            </div>
        </div>

        @endif

    </div>

    {{-- Delete modal (no JS) --}}
    @can(\App\Permissions\PersonPermissions::DELETE)
    <x-modal id="confirmModal{{ $person->id }}"
        title="{{ __('Supprimer cette personne ?') }}"
        class="modal-dialog-centered modal-sm">

        <div class="text-center py-2">
            <div class="rounded-circle bg-danger-subtle d-inline-flex align-items-center justify-content-center mb-3"
                style="width:52px;height:52px;font-size:1.3rem;">
                <i class="bi bi-trash text-danger"></i>
            </div>
            <p class="text-body-secondary small mb-0">
                {{ __('Cette action est irréversible.') }}
                <strong>{{ $person->first_name }} {{ $person->last_name }}</strong>
                {{ __('sera définitivement supprimé.') }}
            </p>
        </div>

        <x-slot name="footer">
            <form method="POST" action="{{ route('admin.people.destroy', $person->id) }}">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-sm btn-danger">
                    <i class="bi bi-trash me-1"></i>{{ __('Supprimer') }}
                </button>
            </form>
        </x-slot>

    </x-modal>
    @endcan

</x-admin-layout>