<h5 class="mb-3">Informations personnelles</h5>

<div class="row g-3">

    <div class="col-md-6">
        <label class="form-label">
            Prénom <span class="text-danger">*</span>
        </label>

        <input
            type="text"
            name="first_name"
            class="form-control @error('first_name') is-invalid @enderror"
            value="{{ old('first_name', $person->first_name ?? '') }}"
        >

        @error('first_name')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        <label class="form-label">
            Nom <span class="text-danger">*</span>
        </label>

        <input
            type="text"
            name="last_name"
            class="form-control @error('last_name') is-invalid @enderror"
            value="{{ old('last_name', $person->last_name ?? '') }}"
        >

        @error('last_name')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">

        <x-phone-input
            :countries="$countries"
            name="phone"
            label="Téléphone principal"
            phoneCodeName="phone_code"
            :value="$person->phone ?? ''"
            :phoneCodeValue="$person->phone_code ?? 226"
            required="true"
        />

    </div>

    <div class="col-md-6">
        <label class="form-label">Genre</label>

        <select
            name="gender"
            class="form-select @error('gender') is-invalid @enderror"
        >
            <option value="">Sélectionner</option>

            <option value="male"
                {{ old('gender', $person->gender ?? '') === 'male' ? 'selected' : '' }}>
                Masculin
            </option>

            <option value="female"
                {{ old('gender', $person->gender ?? '') === 'female' ? 'selected' : '' }}>
                Féminin
            </option>

        </select>

        @error('gender')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

</div>