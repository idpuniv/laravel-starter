@section('title', 'Ajouter des utilisateurs')
<x-admin-layout>
    <div class="container-fluid py-3">

        <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
            <h4 class="mb-0">
                <i class="bi bi-person-plus text-primary me-2"></i>
                Ajouter des utilisateurs à : 
                <span class="text-primary">{{ $team->label }}</span>
            </h4>
            <a href="{{ route('admin.teams.users.index', $team) }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i> Retour
            </a>
        </div>

        <form method="POST" action="{{ route('admin.teams.users.store', $team) }}">
            @csrf

            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-header bg-transparent border-bottom-0 pt-4 px-4">
                    <h5 class="mb-0">
                        <i class="bi bi-people text-primary me-2"></i>
                        Utilisateurs disponibles
                    </h5>
                    <p class="text-muted small mt-1 mb-0">
                        Sélectionnez les utilisateurs à ajouter à cette équipe
                    </p>
                </div>

                <div class="card-body p-4 pt-0">
                    <select name="users[]" multiple class="tom-select" size="8">
                        @foreach($users as $user)
                            <option value="{{ $user->id }}">
                                {{ $user->person->fullName }} ({{ $user->email }})
                            </option>
                        @endforeach
                    </select>

                    @error('users')
                        <div class="text-danger small mt-2">{{ $message }}</div>
                    @enderror
                </div>

                <div class="card-footer bg-transparent border-top-0 pb-4 px-4">
                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('admin.teams.users.index', $team) }}" class="btn btn-light border">
                            Annuler
                        </a>
                        <button class="btn btn-primary px-4">
                            <i class="bi bi-plus-lg me-1"></i> Ajouter
                        </button>
                    </div>
                </div>
            </div>
        </form>

    </div>
    @vite(['resources/js/tom-select.js'])
</x-admin-layout>