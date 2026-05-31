@section('title', 'Gérer les équipes de ' . $user->username)

<x-admin-layout>
    <div class="container py-4">

        {{-- En-tête --}}
        <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 mb-0 d-flex align-items-center gap-2">
                    <i class="bi bi-people text-primary"></i>
                    Gérer les équipes
                </h1>
                <p class="text-muted small mt-1">
                    Utilisateur : <strong>{{ $user->username }}</strong> ({{ $user->email }})
                </p>
            </div>
            <div>
                <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-outline-secondary me-2">
                    <i class="bi bi-arrow-left"></i> Retour
                </a>
            </div>
        </div>

        {{-- Formulaire --}}
        <form action="{{ route('admin.users.teams.update', [$user->id, $team->id]) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-header bg-transparent border-bottom-0 pt-4 px-4">
                    <h5 class="mb-0">Équipes disponibles</h5>
                    <p class="text-muted small mb-0 mt-1">
                        Cochez les équipes auxquelles l'utilisateur appartient
                    </p>
                </div>
                <div class="card-body p-4 pt-0">
                    <div class="row">
                        @php
                            $userTeamIds = $user->teams->pluck('id')->toArray();
                            $allTeams = \App\Models\Team::available()->get();
                        @endphp

                        @forelse($allTeams as $teamItem)
                            <div class="col-md-6 col-lg-4 mb-3">
                                <div class="form-check">
                                    <input type="checkbox"
                                           class="form-check-input"
                                           name="teams[]"
                                           value="{{ $teamItem->id }}"
                                           id="team_{{ $teamItem->id }}"
                                           @checked(in_array($teamItem->id, $userTeamIds))>
                                    <label class="form-check-label d-flex align-items-center gap-2" for="team_{{ $teamItem->id }}">
                                        @if($teamItem->icon)
                                            <i class="bi {{ $teamItem->icon }} text-primary"></i>
                                        @else
                                            <i class="bi bi-building text-secondary"></i>
                                        @endif
                                        <div>
                                            <strong>{{ $teamItem->label }}</strong>
                                            <span class="text-muted small d-block">{{ $teamItem->name }}</span>
                                        </div>
                                    </label>
                                </div>
                                @if($teamItem->description)
                                    <div class="small text-muted mt-1 ms-4 ps-4">
                                        {{ Str::limit($teamItem->description, 60) }}
                                    </div>
                                @endif
                            </div>
                        @empty
                            <div class="col-12">
                                <div class="alert alert-info mb-0">
                                    <i class="bi bi-info-circle"></i> Aucune équipe disponible
                                </div>
                            </div>
                        @endforelse
                    </div>
                </div>
                <div class="card-footer bg-transparent border-top-0 pb-4 px-4">
                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-outline-secondary">
                            Annuler
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save"></i> Enregistrer
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</x-admin-layout>