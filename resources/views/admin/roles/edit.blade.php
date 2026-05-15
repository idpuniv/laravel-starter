@section('title', 'Modifier le rôle')

<x-admin-layout>

<div class="container py-4">

    <h3>Modifier le rôle</h3>

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
                Retour
            </a>

            <button class="btn btn-primary">
                Mettre à jour
            </button>

        </div>

    </form>

</div>

</x-admin-layout>