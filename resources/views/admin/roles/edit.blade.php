@section('title', __('Modifier le rôle'))

<x-admin-layout>

    <div class="container py-4">
        <div class="d-flex align-items-center gap-2">
            <a href="{{ route('admin.roles.index') }}"
               class="btn btn-link text-body icon-circle-xs text-decoration-none hover-bg-secondary-25 p-0 m-0">
                <i class="bi bi-arrow-left"></i>
            </a>
            <h1 class="h5 mb-0 fw-semibold">
                {{ __('Modifier le rôle') }}
                <small class="text-body-secondary fw-normal fs-6">#{{ $role->label }}</small>
            </h1>
        </div>


        <form action="{{ route('admin.roles.update', $role->id) }}" method="POST">
            @csrf
            @method('PUT')

            @include('admin.roles.partials.form', [
            'role' => $role,
            'rolePermissions' => $role->permissions->pluck('name')->toArray()
            ])

            <div class="d-flex justify-content-end gap-2">

                <a href="{{ route('admin.roles.index') }}"
                    class="btn btn-outline-secondary">
                    {{ __('Retour') }}
                </a>

                <button class="btn btn-primary">
                    {{ __('Mettre à jour') }}
                </button>

            </div>

        </form>

    </div>

</x-admin-layout>