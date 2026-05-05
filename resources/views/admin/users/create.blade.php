@section('title', 'Ajouter un utilisateur')
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
                            <x-phone-input 
    :countries="$countries"
    name="phone"
    label="Téléphone principal"
    required="true"
/>
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
                    </div>

                    <hr class="my-4">

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

                    <div id="account-fields-container">
                        @if(isset($person))
                            <div id="account-fields">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label">Email <span class="text-danger">*</span></label>
                                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}">
                                        @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Nom d'utilisateur</label>
                                        <input type="text" name="username" class="form-control @error('username') is-invalid @enderror" value="{{ old('username') }}">
                                        @error('username')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Mot de passe <span class="text-danger">*</span></label>
                                        <input type="password" name="password" class="form-control @error('password') is-invalid @enderror">
                                        @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Confirmation <span class="text-danger">*</span></label>
                                        <input type="password" name="password_confirmation" class="form-control @error('password_confirmation') is-invalid @enderror">
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
                        @endif
                    </div>

                    <template id="account-fields-template">
                        <div id="account-fields">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Email <span class="text-danger">*</span></label>
                                    <input type="email" name="email" class="form-control" value="">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Nom d'utilisateur</label>
                                    <input type="text" name="username" class="form-control" value="">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Mot de passe <span class="text-danger">*</span></label>
                                    <input type="password" name="password" class="form-control">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Confirmation <span class="text-danger">*</span></label>
                                    <input type="password" name="password_confirmation" class="form-control">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Statut</label>
                                    <select name="status" class="form-select">
                                        <option value="active">Actif</option>
                                        <option value="inactive">Inactif</option>
                                        <option value="banned">Banni</option>
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
                    </template>

                    <div class="mt-4 d-flex justify-content-end gap-2">
                        <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">
                            Annuler
                        </a>
                        <button type="submit" class="btn btn-primary">
                            {{ isset($person) ? 'Créer le compte' : 'Enregistrer' }}
                        </button>
                    </div>

                </form>

            </div>
        </div>

    </div>
</x-admin-layout>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Gestion du code téléphone
    const btn = document.getElementById('phoneCodeBtn');
    const phoneCodeInput = document.getElementById('phone_code');

    if (btn && phoneCodeInput) {
        document.querySelectorAll('.dropdown-item').forEach(function(item) {
            item.addEventListener('click', function(e) {
                e.preventDefault();
                const iso2 = this.getAttribute('data-iso2');
                const code = this.getAttribute('data-code');
                
                phoneCodeInput.value = code;
                btn.innerHTML = '<span class="fi fi-' + iso2 + '"></span>';
            });
        });
        
        if (!phoneCodeInput.value) {
            phoneCodeInput.value = '{{ $defaultCountry->phone_code ?? '' }}';
        }
    }

    // Gestion des champs compte avec template
    const createAccountCheckbox = document.getElementById('create_account');
    const container = document.getElementById('account-fields-container');
    const template = document.getElementById('account-fields-template');

    if (createAccountCheckbox && container && template) {
        createAccountCheckbox.addEventListener('change', function() {
            if (this.checked) {
                if (!document.getElementById('account-fields')) {
                    const clone = template.content.cloneNode(true);
                    container.appendChild(clone);
                }
            } else {
                const accountFields = document.getElementById('account-fields');
                if (accountFields) {
                    accountFields.remove();
                }
            }
        });
    }
});
</script>
