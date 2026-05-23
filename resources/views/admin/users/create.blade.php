@section('title', 'Créer un utilisateur')

<x-admin-layout>

    <div class="container py-4">


        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="mb-0">Créer un utilisateur</h3>
            <a href="{{ route('admin.people.show', $person->id) }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> Retour
            </a>
        </div>

        
        {{-- Carte informations personnelles --}}
        <div class="card shadow-sm border-0 rounded-4 mb-4 mt-3">
            @include('admin.people.partials.profile-photo')
            <div class="card-body p-4 pt-0">
                <div class="row g-3">
                    
                    {{-- Informations --}}
                    <div class="col-md-10">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="rounded-3 p-3">
                                    <div class="text-muted small mb-1">
                                        <i class="bi bi-person"></i> Nom complet
                                    </div>
                                    <div class="fw-semibold fs-5">
                                        {{ $person->first_name }} {{ $person->last_name }}
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="rounded-3 p-3">
                                    <div class="text-muted small mb-1">
                                        <i class="bi bi-telephone"></i> Téléphone
                                    </div>
                                    <div class="fw-semibold">
                                        @if($person->full_phone)
                                            {{ $person->full_phone }}
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="rounded-3 p-3">
                                    <div class="text-muted small mb-1">
                                        <i class="bi bi-geo-alt"></i> Pays
                                    </div>
                                    <div class="fw-semibold">
                                        @if($person->country)
                                            {{ $person->country->name }}
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="rounded-3 p-3">
                                    <div class="text-muted small mb-1">
                                        <i class="bi bi-calendar3"></i> Date d'inscription
                                    </div>
                                    <div class="fw-semibold">
                                        {{ $person->created_at->format('d/m/Y') }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Formulaire de création --}}
        <div class="card shadow-sm border-0 rounded-4">
            <div class="card-header bg-transparent border-bottom-0 pt-4 px-4">
                <div class="d-flex align-items-center gap-2">
                    <i class="bi bi-person-plus text-primary fs-4"></i>
                    <h5 class="mb-0">Informations du compte</h5>
                </div>
            </div>
            <div class="card-body p-4 pt-0">
                <form method="POST" action="{{ route('admin.users.store') }}">
                    @csrf
                    @isset($person)
                        <input type="hidden" name="person_id" value="{{ $person->id }}">
                    @endisset
                    
                    @include('admin.users.partials.form')

                    <div class="d-flex justify-content-end gap-2 mt-4">
                        <a href="{{ route('admin.people.index') }}" class="btn btn-outline-secondary">
                            Annuler
                        </a>
                        <button class="btn btn-primary">
                          Créer le compte
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>

</x-admin-layout>