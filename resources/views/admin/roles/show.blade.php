@section('title', __('titles.roles.show', ['name' => $role->label]))

<x-admin-layout>
    <div class="container py-4">

        <div class="card border-0">
            <div class="card-body">

                @if ($role->permissions->isNotEmpty())

                    <div class="mb-3">
                        <strong>{{ __('Permissions') }} :</strong>
                    </div>

                    <div class="d-flex flex-wrap gap-2">
                        @foreach ($role->permissions as $permission)
                            <span class="badge bg-primary">
                                {{ $permission->label ?? $permission->name }}
                            </span>
                        @endforeach
                    </div>

                @else

                    <div class="text-center py-4">

                        <div class="icon-circle-lg mx-auto mb-3">
                            <i class="bi bi-shield-slash fs-2"></i>
                        </div>

                        <p class="text-muted mb-3">
                            {{ __('messages.roles.no_permissions', [
                                'role' => $role->label ?? $role->name
                            ]) }}
                        </p>

                        @can(\App\Permissions\RolePermissions::UPDATE)
                            <a href="{{ route('admin.roles.edit', $role->id) }}" class="btn btn-primary">
                                <i class="bi bi-plus-lg"></i>
                                {{ __('Ajouter des permissions') }}
                            </a>
                        @endcan

                    </div>

                @endif

            </div>
        </div>

    </div>
</x-admin-layout>