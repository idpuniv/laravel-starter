@section('title', 'Modifier ' . $user->name)

<x-admin-layout>
<div class="container py-4">

    <h3>Modifier l’utilisateur</h3>

    <div class="card">
        <div class="card-body">

            <form method="POST" action="{{ route('admin.users.update', $user->id) }}">
                @csrf
                @method('PUT')

                <div class="row g-3">

                    {{-- Nom --}}
                    <div class="col-md-6">
                        <label class="form-label">Nom</label>
                        <input type="text"
                               name="name"
                               class="form-control @error('name') is-invalid @enderror"
                               value="{{ old('name', $user->name) }}">
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Username --}}
                    <div class="col-md-6">
                        <label class="form-label">Username</label>
                        <input type="text"
                               name="username"
                               class="form-control @error('username') is-invalid @enderror"
                               value="{{ old('username', $user->username) }}">
                        @error('username')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Email --}}
                    <div class="col-md-6">
                        <label class="form-label">Email</label>
                        <input type="email"
                               name="email"
                               class="form-control @error('email') is-invalid @enderror"
                               value="{{ old('email', $user->email) }}">
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- STATUS --}}
                    <div class="col-md-6">
                        <label class="form-label">Statut</label>

                        <select name="status"
                                class="form-select @error('status') is-invalid @enderror">
                            <option value="active" @selected(old('status', $user->status) === 'active')>
                                Actif
                            </option>

                            <option value="inactive" @selected(old('status', $user->status) === 'inactive')>
                                Inactif
                            </option>
                        </select>

                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- TEAM --}}
                    @if($teams && $teams->count() > 1)
                        <div class="col-md-6">
                            <label class="form-label">Équipe</label>

                            <select name="team_id"
                                    class="form-select @error('team_id') is-invalid @enderror">
                                <option value="">-- Aucune équipe --</option>

                                @foreach ($teams as $team)
                                    <option value="{{ $team->id }}"
                                        @selected(old('team_id', $user->team_id) == $team->id)>
                                        {{ $team->name }}
                                    </option>
                                @endforeach
                            </select>

                            @error('team_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    @endif

                </div>

                {{-- ACTIONS --}}
                <div class="mt-4 d-flex justify-content-end">
                    <button class="btn btn-primary">
                        Mettre à jour
                    </button>
                </div>

            </form>

        </div>
    </div>

</div>
</x-admin-layout>