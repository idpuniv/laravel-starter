@section('title', isset($person) ? 'Ajouter un compte' : 'Ajouter une personne')
<x-admin-layout>
    <div class="container py-4">

        <div class="card">
            <div class="card-body">

                @if(isset($person))
                    <div class="alert alert-info mb-4">
                        <i class="bi bi-info-circle"></i>
                        Création d'un compte pour : <strong>{{ $person->first_name }} {{ $person->last_name }}</strong>
                    </div>
                @endif

                <form method="POST" action="{{ route('admin.users.store') }}">
                    @csrf

                    @if(isset($person))
                        <input type="hidden" name="person_id" value="{{ $person->id }}">
                    @endif

                    <h5 class="mb-3">Informations personnelles</h5>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Prénom <span class="text-danger">*</span></label>
                            <input type="text" name="first_name"
                                   class="form-control @error('first_name') is-invalid @enderror"
                                   value="{{ old('first_name', $person->first_name ?? '') }}"
                                   {{ isset($person) ? 'readonly' : '' }}>
                            @error('first_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Nom <span class="text-danger">*</span></label>
                            <input type="text" name="last_name"
                                   class="form-control @error('last_name') is-invalid @enderror"
                                   value="{{ old('last_name', $person->last_name ?? '') }}"
                                   {{ isset($person) ? 'readonly' : '' }}>
                            @error('last_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Téléphone</label>
                            
                            @php
                                $defaultCountry = $countries->firstWhere('iso2', 'BF') ?? $countries->first();
                                $defaultPhoneCode = old('phone_code', $defaultCountry->phone_code ?? '');
                            @endphp
                            
                            {{-- Champ caché pour le code téléphone --}}
                            <input type="hidden" name="phone_code" id="phone_code" value="{{ $defaultPhoneCode }}">
                            
                            <div class="input-group">
                                <button class="btn btn-outline-secondary dropdown-toggle d-flex align-items-center justify-content-center"
                                        type="button"
                                        id="phoneCodeBtn"
                                        data-bs-toggle="dropdown"
                                        aria-expanded="false"
                                        style="width: 70px;">
                                    <span class="fi fi-{{ strtolower($defaultCountry->iso2) }}"></span>
                                </button>

                                <ul class="dropdown-menu" style="max-height: 300px; overflow-y: auto;">
                                    @foreach($countries as $country)
                                        <li>
                                            <a class="dropdown-item d-flex align-items-center gap-2"
                                               href="#"
                                               data-iso2="{{ strtolower($country->iso2) }}"
                                               data-code="{{ $country->phone_code }}">
                                                <span class="fi fi-{{ strtolower($country->iso2) }}"></span>
                                                <span>{{ $country->name }}</span>
                                                <span class="text-muted ms-auto">+{{ $country->phone_code }}</span>
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>

                                <input type="tel"
                                       name="phone"
                                       class="form-control @error('phone') is-invalid @enderror"
                                       value="{{ old('phone', $person->phone ?? '') }}"
                                       placeholder="77 123 45 67">
                            </div>
                            @error('phone')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Genre</label>
                            <select name="gender" class="form-select @error('gender') is-invalid @enderror">
                                <option value="">Sélectionner</option>
                                <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Masculin</option>
                                <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Féminin</option>
                            </select>
                            @error('gender')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Pays</label>
                            <select name="country_id" class="form-select @error('country_id') is-invalid @enderror">
                                <option value="">Sélectionner</option>
                                @foreach($countries as $country)
                                    <option value="{{ $country->id }}" {{ old('country_id') == $country->id ? 'selected' : '' }}>
                                        {{ $country->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('country_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <hr class="my-4">

                    {{-- Section compte utilisateur --}}
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="mb-0">Compte utilisateur</h5>
                        @if(!isset($person))
                            <div class="form-check">
                                <input type="checkbox" name="create_account" id="create_account" class="form-check-input" value="1">
                                <label class="form-check-label" for="create_account">
                                    Créer un compte
                                </label>
                            </div>
                        @endif
                    </div>

                    <div id="account-fields" style="{{ !isset($person) ? 'display: none;' : '' }}">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Email @if(isset($person)) <span class="text-danger">*</span> @endif</label>
                                <input type="email" name="email"
                                       class="form-control @error('email') is-invalid @enderror"
                                       value="{{ old('email') }}">
                                @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Nom d'utilisateur</label>
                                <input type="text" name="username"
                                       class="form-control @error('username') is-invalid @enderror"
                                       value="{{ old('username') }}">
                                @error('username')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Mot de passe @if(isset($person)) <span class="text-danger">*</span> @endif</label>
                                <input type="password" name="password"
                                       class="form-control @error('password') is-invalid @enderror">
                                @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Confirmation @if(isset($person)) <span class="text-danger">*</span> @endif</label>
                                <input type="password" name="password_confirmation"
                                       class="form-control @error('password_confirmation') is-invalid @enderror">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Statut</label>
                                <select name="status" class="form-select">
                                    <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Actif</option>
                                    <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactif</option>
                                    <option value="banned" {{ old('status') == 'banned' ? 'selected' : '' }}>Banni</option>
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Rôles</label>
                                <select name="roles[]" class="form-select" multiple size="3">
                                    @foreach($roles ?? [] as $role)
                                        <option value="{{ $role->id }}">{{ $role->name }}</option>
                                    @endforeach
                                </select>
                                <small class="text-muted">Ctrl+clic pour sélectionner plusieurs</small>
                            </div>
                        </div>
                    </div>

                    <div class="mt-4 d-flex justify-content-end gap-2">
                        <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">
                            Annuler
                        </a>
                        <button class="btn btn-primary">
                            {{ isset($person) ? 'Créer le compte' : 'Enregistrer' }}
                        </button>
                    </div>

                </form>

            </div>
        </div>

    </div>
</x-admin-layout>

<script>
document.addEventListener('DOMContentLoaded', function () {
    // Gestion du code téléphone
    const btn = document.getElementById('phoneCodeBtn');
    const phoneCodeInput = document.getElementById('phone_code');

    document.querySelectorAll('.dropdown-item').forEach(item => {
        item.addEventListener('click', function (e) {
            e.preventDefault();
            const iso2 = this.dataset.iso2;
            const code = this.dataset.code;
            
            // Mettre à jour le champ caché avec le code pays
            if (phoneCodeInput) {
                phoneCodeInput.value = code;
            }
            
            // Mettre à jour l'affichage du drapeau
            btn.innerHTML = `<span class="fi fi-${iso2}"></span>`;
        });
    });

    // Gestion de l'affichage des champs compte
    const createAccountCheckbox = document.getElementById('create_account');
    const accountFields = document.getElementById('account-fields');

    if (createAccountCheckbox) {
        createAccountCheckbox.addEventListener('change', function() {
            accountFields.style.display = this.checked ? 'block' : 'none';
        });
    }
    
    // Initialiser le code téléphone par défaut si le champ est vide
    if (phoneCodeInput && !phoneCodeInput.value) {
        const defaultCode = '{{ $defaultCountry->phone_code ?? '' }}';
        phoneCodeInput.value = defaultCode;
    }
});
</script>