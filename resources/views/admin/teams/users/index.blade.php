@extends('layouts.admin-layout')

@section('title', 'Utilisateurs de l\'équipe')

@section('content')
<div class="container-fluid py-4">

    {{-- En-tête --}}
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <h1 class="h3 fw-semibold m-0 d-flex align-items-center gap-2">
                <i class="bi bi-people text-primary"></i>
                Utilisateurs de l'équipe
            </h1>
            <p class="text-muted small mt-1 mb-0">
                {{ $team->label }} ({{ $team->name }}) • {{ $users->total() }} membre(s)
            </p>
        </div>

        @can(\App\Permissions\TeamPermissions::UPDATE)
            <a href="{{ route('admin.teams.users.create', $team) }}" class="btn btn-primary">
                <i class="bi bi-plus-lg me-2"></i>Ajouter des utilisateurs
            </a>
        @endcan
    </div>

    {{-- Table --}}
    <div class="card shadow-sm border-0 rounded-4">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th class="ps-4 py-3">
                                <span class="text-muted small fw-semibold">Utilisateur</span>
                            </th>
                            <th class="py-3">
                                <span class="text-muted small fw-semibold">Email</span>
                            </th>
                            <th class="pe-4 py-3 text-end" width="120">
                                <span class="text-muted small fw-semibold">Actions</span>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $user)
                            <tr>
                                <td class="ps-4">
                                    <div class="d-flex align-items-center gap-2">
                                        <div>
                                            <div class="fw-semibold">{{ $user->name }}</div>
                                            @if($user->person)
                                                <div class="text-muted small">{{ $user->person->fullName ?? '' }}</div>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $user->email }}</td>
                                <td class="pe-4 py-3 text-end">
                                    <div class="d-flex gap-2 justify-content-end">
                                        @can(\App\Permissions\TeamPermissions::UPDATE)
                                            <a href="#"
                                               class="icon-circle-xs text-decoration-none text-body hover-bg-secondary-25"
                                               data-bs-toggle="modal"
                                               data-bs-target="#confirmModal"
                                               data-url="{{ route('admin.teams.users.destroy', [$team, $user]) }}"
                                               data-method="DELETE"
                                               title="Retirer">
                                                <i class="bi bi-person-dash fs-6"></i>
                                            </a>
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center py-5">
                                    <i class="bi bi-people fs-1 text-muted d-block mb-2"></i>
                                    <p class="text-muted mb-0">Aucun utilisateur dans cette équipe</p>
                                    @can(\App\Permissions\TeamPermissions::UPDATE)
                                        <a href="{{ route('admin.teams.users.create', $team) }}" class="btn btn-sm btn-primary mt-3">
                                            <i class="bi bi-plus-lg"></i> Ajouter des utilisateurs
                                        </a>
                                    @endcan
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Pagination --}}
    @if ($users->hasPages())
        <div class="mt-4 d-flex justify-content-end">
            {{ $users->links() }}
        </div>
    @endif

</div>
@endsection