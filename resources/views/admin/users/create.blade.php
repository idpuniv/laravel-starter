@section('title', 'Ajouter un utilisateur')

<x-admin-layout>
    <div class="container py-4">

        <h3>Créer un utilisateur</h3>

        <div class="card">
            <div class="card-body">

                <form method="POST" action="{{ route('admin.users.store') }}">
                    @csrf

                    {{-- PERSONNE --}}
                    <h5 class="mb-3 mt-2">Informations personnelles</h5>

                    <div class="row g-3">

                        <div class="col-md-6">
                            <label class="form-label">Nom</label>
                            <input type="text" name="nom"
                                   class="form-control @error('nom') is-invalid @enderror"
                                   value="{{ old('nom') }}">
                            @error('nom')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Prénom</label>
                            <input type="text" name="prenom"
                                   class="form-control @error('prenom') is-invalid @enderror"
                                   value="{{ old('prenom') }}">
                            @error('prenom')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- TÉLÉPHONE AVEC DROPDOWN --}}
                        <div class="col-md-6">
                            <label class="form-label">Téléphone</label>
                            <div class="input-group">
                                <button class="btn btn-outline-secondary dropdown-toggle" 
                                        type="button" 
                                        data-bs-toggle="dropdown" 
                                        aria-expanded="false"
                                        id="phoneCodeBtn"
                                        style="min-width: 120px;">
                                    @php
                                        $defaultCountry = $countries->firstWhere('iso2', 'SN') ?? $countries->first();
                                    @endphp
                                    <span class="fi fi-{{ strtolower($defaultCountry?->iso2 ?? 'sn') }}"></span>
                                    <span class="ms-1">{{ $defaultCountry?->phone_code ?? '+221' }}</span>
                                </button>
                                <ul class="dropdown-menu" style="max-height: 300px; overflow-y: auto;">
                                    @foreach($countries as $country)
                                        <li>
                                            <a class="dropdown-item" 
                                               href="#"
                                               data-code="{{ $country->phone_code }}"
                                               data-iso2="{{ strtolower($country->iso2) }}">
                                                <span class="fi fi-{{ strtolower($country->iso2) }} me-2"></span>
                                                {{ $country->name }} 
                                                <span class="text-muted">({{ $country->phone_code }})</span>
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                                <input type="tel" 
                                       name="phone" 
                                       id="phoneNumber"
                                       class="form-control @error('phone') is-invalid @enderror"
                                       value="{{ old('phone') }}"
                                       placeholder="77 123 45 67">
                            </div>
                            <input type="hidden" name="phone_code" id="hiddenPhoneCode" value="{{ $defaultCountry?->phone_code ?? '' }}">
                            <small class="text-muted">Sélectionnez l'indicatif puis entrez le numéro</small>
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            @error('phone_code')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Genre</label>
                            <select name="gender"
                                    class="form-select @error('gender') is-invalid @enderror">
                                <option value="">-- Choisir --</option>
                                <option value="male" @selected(old('gender') === 'male')>Homme</option>
                                <option value="female" @selected(old('gender') === 'female')>Femme</option>
                            </select>
                            @error('gender')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- PAYS DE RÉSIDENCE --}}
                        <div class="col-md-6">
                            <label class="form-label">Pays de résidence</label>
                            <select name="country_id"
                                    class="form-select @error('country_id') is-invalid @enderror">
                                <option value="">-- Sélectionner --</option>
                                @foreach ($countries as $country)
                                    <option value="{{ $country->id }}" @selected(old('country_id') == $country->id)>
                                        {{ $country->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('country_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                    </div>

                    {{-- COMPTE UTILISATEUR --}}
                    <h5 class="mb-3 mt-4">Informations du compte</h5>

                    <div class="row g-3">

                        <div class="col-md-6">
                            <label class="form-label">Username</label>
                            <input type="text" name="username"
                                   class="form-control @error('username') is-invalid @enderror"
                                   value="{{ old('username') }}">
                            @error('username')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Email</label>
                            <input type="email" name="email"
                                   class="form-control @error('email') is-invalid @enderror"
                                   value="{{ old('email') }}">
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        @if(isset($teams) && $teams->count() > 1)
                            <div class="col-md-6">
                                <label class="form-label">Équipe</label>
                                <select name="team_id"
                                        class="form-select @error('team_id') is-invalid @enderror">
                                    <option value="">-- Sélectionner une équipe --</option>
                                    @foreach ($teams as $team)
                                        <option value="{{ $team->id }}" @selected(old('team_id') == $team->id)>
                                            {{ $team->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('team_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        @endif

                        <div class="col-md-6">
                            <label class="form-label">Mot de passe</label>
                            <input type="password" name="password"
                                   class="form-control @error('password') is-invalid @enderror">
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Confirmation</label>
                            <input type="password" name="password_confirmation"
                                   class="form-control @error('password_confirmation') is-invalid @enderror">
                            @error('password_confirmation')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                    </div>

                    {{-- ACTIONS --}}
                    <div class="mt-4 d-flex justify-content-end gap-2">
                        <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">
                            Annuler
                        </a>
                        <button class="btn btn-primary">
                            Créer
                        </button>
                    </div>

                </form>

            </div>
        </div>

    </div>
</x-admin-layout>

@push('scripts')
<script>
    // Attendre que le DOM soit chargé
    document.addEventListener('DOMContentLoaded', function() {
        // Sélectionner tous les éléments du dropdown
        const dropdownItems = document.querySelectorAll('.dropdown-item');
        const phoneCodeBtn = document.getElementById('phoneCodeBtn');
        const hiddenPhoneCode = document.getElementById('hiddenPhoneCode');
        
        dropdownItems.forEach(item => {
            item.addEventListener('click', function(e) {
                e.preventDefault();
                
                // Récupérer les données
                const code = this.dataset.code;
                const iso2 = this.dataset.iso2;
                
                // Mettre à jour le bouton
                if (phoneCodeBtn) {
                    phoneCodeBtn.innerHTML = `
                        <span class="fi fi-${iso2}"></span>
                        <span class="ms-1">${code}</span>
                    `;
                }
                
                // Mettre à jour le champ caché
                if (hiddenPhoneCode) {
                    hiddenPhoneCode.value = code;
                }
            });
        });
    });
</script>
@endpush