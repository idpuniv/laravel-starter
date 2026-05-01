@props([
    'name' => 'phone',
    'label' => 'Téléphone',
    'required' => false,
    'value' => '',
    'countries' => [],
    'defaultCountry' => null,
    'phoneCodeName' => 'phone_code',
    'placeholder' => '77 123 45 67'
])

@php
    $defaultCountry = $defaultCountry ?? ($countries->firstWhere('iso2', 'BF') ?? $countries->first());
    $defaultPhoneCode = old($phoneCodeName, $defaultCountry->phone_code ?? '');
    $inputId = 'phone_' . md5($name);
    $btnId = 'phone_btn_' . md5($name);
    $codeInputId = 'phone_code_' . md5($name);
@endphp

<div class="phone-input-component" data-instance="{{ $inputId }}">
    <label class="form-label">
        {{ $label }}
        @if($required) <span class="text-danger">*</span> @endif
    </label>
    
    <input type="hidden" name="{{ $phoneCodeName }}" id="{{ $codeInputId }}" value="{{ $defaultPhoneCode }}">
    
    <div class="input-group">
        <button class="border-0 dropdown-toggle d-flex align-items-center justify-content-center"
                type="button"
                id="{{ $btnId }}"
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
                       data-code="{{ $country->phone_code }}"
                       data-target="{{ $btnId }}"
                       data-code-input="{{ $codeInputId }}">
                        <span class="fi fi-{{ strtolower($country->iso2) }}"></span>
                        <span>{{ $country->name }}</span>
                        <span class="text-muted ms-auto">+{{ $country->phone_code }}</span>
                    </a>
                </li>
            @endforeach
        </ul>

        <input type="tel"
               name="{{ $name }}"
               id="{{ $inputId }}"
               class="form-control @error($name) is-invalid @enderror"
               value="{{ old($name, $value) }}"
               placeholder="{{ $placeholder }}">
    </div>
    
    @error($name)
    <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>