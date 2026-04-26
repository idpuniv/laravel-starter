
<x-admin-layout>
<div class="container py-4">

    <h3>Détail du rôle</h3>

    <div class="card">
        <div class="card-body">

            <p><strong>Nom :</strong> {{ $role->name }}</p>

            <p><strong>Permissions :</strong></p>

            @foreach($role->permissions as $permission)
                <span class="badge bg-primary">
                    {{ $permission->name }}
                </span>
            @endforeach

        </div>
    </div>

    <a href="{{ route('admin.roles.index') }}" class="btn btn-secondary mt-3">
        Retour
    </a>

</div>
</x-admin-layout>