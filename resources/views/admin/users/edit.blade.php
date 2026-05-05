@section('title', 'Modifier une personne')

<x-admin-layout>
    <div class="container py-4">

        <div class="card">
            <div class="card-body">

                <form method="POST" action="{{ route('admin.users.update', $person->id) }}">
                    @csrf
                    @method('PUT')

                    {{-- ========================= --}}
                    {{-- PERSONNE --}}
                    {{-- ========================= --}}
                    <h5 class="mb-3">Informations personnelles</h5>

                    <div class="row g-3">

                        <div class="col-md-6">
                            <label class="form-label">Prénom *</label>
                            <input type="text" name="first_name"
                                class="form-control @error('first_name') is-invalid @enderror"
                                value="{{ old('first_name', $person->first_name) }}">
                            @error('first_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Nom *</label>
                            <input type="text" name="last_name"
                                class="form-control @error('last_name') is-invalid @enderror"
                                value="{{ old('last_name', $person->last_name) }}">
                            @error('last_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <x-phone-input :countries="$countries" name="phone" label="Téléphone" :value="$person->phone"
                                required="true" />
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Genre</label>
                            <select name="gender" class="form-select">
                                <option value="">Sélectionner</option>
                                <option value="male" {{ old('gender', $person->gender) == 'male' ? 'selected' : '' }}>
                                    Masculin</option>
                                <option value="female"
                                    {{ old('gender', $person->gender) == 'female' ? 'selected' : '' }}>Féminin</option>
                            </select>
                        </div>

                    </div>

                    <hr class="my-4">

                    {{-- ========================= --}}
                    {{-- COMPTE USER --}}
                    {{-- ========================= --}}
                    <div class="d-flex justify-content-between align-items-center mb-3">

                        <h5 class="mb-0">Compte utilisateur</h5>

                        @if (!$person->user)
                            <div class="form-check">
                                <input type="checkbox" id="create_account" name="create_account" value="1"
                                    class="form-check-input">
                                <label class="form-check-label" for="create_account">
                                    Créer un compte
                                </label>
                            </div>
                        @endif

                    </div>

                    <div id="account-fields-container">

                        @if ($person->user)
                            <div id="account-fields">
                                <div class="row g-3">

                                    <div class="col-md-6">
                                        <label class="form-label">Email *</label>
                                        <input type="email" name="email" class="form-control"
                                            value="{{ old('email', $person->user->email) }}">
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">Nom d'utilisateur</label>
                                        <input type="text" name="username" class="form-control"
                                            value="{{ old('username', $person->user->username) }}">
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">Nouveau mot de passe</label>
                                        <input type="password" name="password" class="form-control">
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">Confirmation</label>
                                        <input type="password" name="password_confirmation" class="form-control">
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">Statut</label>
                                        <select name="status" class="form-select">
                                            <option value="active"
                                                {{ old('status', $person->user->status) == 'active' ? 'selected' : '' }}>
                                                Actif</option>
                                            <option value="inactive"
                                                {{ old('status', $person->user->status) == 'inactive' ? 'selected' : '' }}>
                                                Inactif</option>
                                            <option value="banned"
                                                {{ old('status', $person->user->status) == 'banned' ? 'selected' : '' }}>
                                                Banni</option>
                                        </select>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">Équipe</label>
                                        <select name="team_id" class="form-select">
                                            <option value="">--</option>
                                            @foreach ($teams as $team)
                                                <option value="{{ $team->id }}"
                                                    {{ old('team_id', $person->user->team_id) == $team->id ? 'selected' : '' }}>
                                                    {{ $team->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                </div>
                            </div>
                        @endif

                    </div>

                    {{-- TEMPLATE JS --}}
                    <template id="account-fields-template">
                        <div id="account-fields">
                            <div class="row g-3">

                                <div class="col-md-6">
                                    <label class="form-label">Email *</label>
                                    <input type="email" name="email" class="form-control">
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Nom d'utilisateur</label>
                                    <input type="text" name="username" class="form-control">
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Mot de passe *</label>
                                    <input type="password" name="password" class="form-control">
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Confirmation *</label>
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
                                    <label class="form-label">Équipe</label>
                                    <select name="team_id" class="form-select">
                                        <option value="">--</option>
                                        @foreach ($teams as $team)
                                            <option value="{{ $team->id }}">{{ $team->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                            </div>
                        </div>
                    </template>

                    <div class="mt-4 d-flex justify-content-end gap-2">
                        <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">
                            Annuler
                        </a>
                        <button type="submit" class="btn btn-primary">
                            Mettre à jour
                        </button>
                    </div>

                </form>

            </div>
        </div>

    </div>
</x-admin-layout>

{{-- JS --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {

        const checkbox = document.getElementById('create_account');
        const container = document.getElementById('account-fields-container');
        const template = document.getElementById('account-fields-template');

        if (!checkbox || !container || !template) return;

        function render() {
            if (!document.getElementById('account-fields')) {
                container.appendChild(template.content.cloneNode(true));
            }
        }

        function remove() {
            const el = document.getElementById('account-fields');
            if (el) el.remove();
        }

        if (checkbox.checked) {
            render();
        } else {
            remove();
        }

        checkbox.addEventListener('change', function() {
            this.checked ? render() : remove();
        });

    });
</script>
