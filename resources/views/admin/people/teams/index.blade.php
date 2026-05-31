@section('title', 'Gestion des équipes de '. $user->person->fullName)
<x-admin-layout>
    <div class="">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
            <div>
                <h1 class="h3 fw-semibold m-0 d-flex align-items-center gap-2">
                    Gestion des équipes de {{ $user->person->fullName }}
                </h1>
                <p class="text-muted small mt-1 mb-0">
                    {{ $teams->total() }} équipe(s) au total
                </p>
            </div>

            @can(\App\Permissions\UserPermissions::UPDATE_TEAM)
            <a href="{{route('admin.users.teams.create', ['user' => $user])}}" class="btn btn-primary">
                <i class="bi bi-plus-lg me-2"></i>Ajouté à une équipe
            </a>
            @endcan
        </div>
        @include('admin.people.partials.teams')
    </div>
</x-admin-layout>