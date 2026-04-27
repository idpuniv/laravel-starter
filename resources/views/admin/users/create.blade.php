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

                        <div class="col-md-6">
                            <label class="form-label">Téléphone</label>
                            <input type="text" name="phone"
                                   class="form-control @error('phone') is-invalid @enderror"
                                   value="{{ old('phone') }}">
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Code pays</label>
                            <input type="text" name="phone_code"
                                   class="form-control @error('phone_code') is-invalid @enderror"
                                   value="{{ old('phone_code') }}">
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

                        @if(isset($countries))
                            <div class="col-md-6">
                                <label class="form-label">Pays</label>
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
                        @endif

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

                        @if ($teams && $teams->count() > 1)
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