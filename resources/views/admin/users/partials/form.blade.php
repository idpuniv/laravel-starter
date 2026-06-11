<div class="row g-3">
    
    <div class="col-md-6">
        <label class="form-label">
            Email <span class="text-danger">*</span>
        </label>

        <input type="email"
               name="email"
               class="form-control @error('email') is-invalid @enderror"
               value="{{ old('email', $user->email ?? '') }}">

        @error('email')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    {{-- USERNAME --}}
    <div class="col-md-6">
        <label class="form-label">Nom d'utilisateur</label>

        <input type="text"
               name="username"
               class="form-control @error('username') is-invalid @enderror"
               value="{{ old('username', $user->username ?? '') }}">

        @error('username')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    {{-- PASSWORD --}}
    <div class="col-md-6">
        <label class="form-label">
            Mot de passe
            @if(!isset($user)) <span class="text-danger">*</span> @endif
        </label>

        <input type="password"
               name="password"
               class="form-control @error('password') is-invalid @enderror">

        @error('password')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    {{-- PASSWORD CONFIRM --}}
    <div class="col-md-6">
        <label class="form-label">
            Confirmation
            @if(!isset($user)) <span class="text-danger">*</span> @endif
        </label>

        <input type="password"
               name="password_confirmation"
               class="form-control">
    </div>

    {{-- TEAM --}}
    <div class="col-md-6">
        <label class="form-label">Équipe</label>

        <select name="team_id"
                class="form-select @error('team_id') is-invalid @enderror">

            <option value="">-- Sélectionner --</option>

            @foreach($teams ?? [] as $team)
                <option value="{{ $team->id }}"
                    {{ old('team_id', $user->team_id ?? '') == $team->id ? 'selected' : '' }}>
                    {{ $team->name }}
                </option>
            @endforeach

        </select>

        @error('team_id')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    {{-- STATUS (SWITCH) --}}
    <div class="col-md-6">
        <label class="form-label">Statut</label>

        @php
            $currentStatus = old('status', $user->status ?? 'active');
        @endphp

        <div class="form-check form-switch" style="min-height: 38px; display: flex; align-items: center;">
            <input type="checkbox"
                   class="form-check-input"
                   name="status"
                   id="statusSwitch"
                   value="active"
                   @checked($currentStatus === 'active')
                   style="width: 3rem; height: 1.5rem; margin-top: 0;">
            <label class="form-check-label ms-2" for="statusSwitch"></label>
        </div>

        @error('status')
            <div class="text-danger small mt-1">{{ $message }}</div>
        @enderror
    </div>

</div>