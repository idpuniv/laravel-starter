@section('title', 'Paramètres')
<x-admin-layout>
    @vite('resources/js/pages/settings.js')

    <div class="container py-4">
        {{-- En-tête --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h2 mb-1">⚙️ Paramètres</h1>
                <p class="text-muted mb-0">Gérez les paramètres de l'application</p>
            </div>
            <span class="badge bg-secondary">{{ count($settings) }} paramètres</span>
        </div>

        {{-- Accordéon --}}
        <div class="accordion" id="settingsAccordion">
            @foreach ($groups as $groupKey => $group)
                <div class="accordion-item">
                    {{-- En-tête du groupe --}}
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" 
                                data-bs-toggle="collapse" 
                                data-bs-target="#collapse_{{ $groupKey }}">
                            <i class="{{ $group['icon'] }} me-2"></i>
                            <span>{{ $group['label'] }}</span>
                            @if(isset($group['description']))
                                <small class="text-muted ms-2">{{ $group['description'] }}</small>
                            @endif
                        </button>
                    </h2>

                    {{-- Corps du groupe --}}
                    <div id="collapse_{{ $groupKey }}" class="accordion-collapse collapse">
                        <div class="accordion-body">
                            @foreach ($group['fields'] as $fieldKey)
                                @php
                                    $field = $fields[$fieldKey];
                                    $value = $settings[$fieldKey] ?? null;
                                    $isSystem = str_starts_with($fieldKey, 'system.');
                                    $disabled = $isSystem && !Auth::user()?->can(\App\Permissions\SystemPermissions::EDIT_SETTINGS);
                                    $fieldId = 'field_' . Str::slug($fieldKey);
                                @endphp

                                <div class="mb-3 pb-3 {{ !$loop->last ? 'border-bottom' : '' }}">
                                    {{-- Champ --}}
                                    @switch($field['type'])
                                        @case('boolean')
                                            <div class="form-check form-switch">
                                                <input class="form-check-input settings-auto-save" 
                                                       type="checkbox" 
                                                       role="switch"
                                                       id="{{ $fieldId }}"
                                                       data-key="{{ $fieldKey }}"
                                                       {{ $value ? 'checked' : '' }}
                                                       {{ $disabled ? 'disabled' : '' }}>
                                                <label class="form-check-label" for="{{ $fieldId }}">
                                                    <strong>{{ $field['label'] }}</strong>
                                                    @if($isSystem)
                                                        <span class="badge bg-light text-dark ms-1">système</span>
                                                    @endif
                                                </label>
                                                @if(isset($field['description']))
                                                    <div class="form-text">{{ $field['description'] }}</div>
                                                @endif
                                            </div>
                                            @break

                                        @case('select')
                                            <label class="form-label fw-semibold" for="{{ $fieldId }}">
                                                {{ $field['label'] }}
                                                @if($isSystem)
                                                    <span class="badge bg-light text-dark ms-1">système</span>
                                                @endif
                                            </label>
                                            <select class="form-select settings-auto-save" 
                                                    id="{{ $fieldId }}" 
                                                    data-key="{{ $fieldKey }}"
                                                    {{ $disabled ? 'disabled' : '' }}>
                                                @foreach($field['options'] as $optValue => $optLabel)
                                                    <option value="{{ $optValue }}" 
                                                            {{ $value == $optValue ? 'selected' : '' }}>
                                                        {{ $optLabel }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @if(isset($field['description']))
                                                <div class="form-text">{{ $field['description'] }}</div>
                                            @endif
                                            @break

                                        @case('number')
                                            <label class="form-label fw-semibold" for="{{ $fieldId }}">
                                                {{ $field['label'] }}
                                                @if($isSystem)
                                                    <span class="badge bg-light text-dark ms-1">système</span>
                                                @endif
                                            </label>
                                            <input type="number" 
                                                   class="form-control settings-auto-save"
                                                   id="{{ $fieldId }}" 
                                                   data-key="{{ $fieldKey }}"
                                                   value="{{ $value }}"
                                                   {{ $disabled ? 'disabled' : '' }}>
                                            @if(isset($field['description']))
                                                <div class="form-text">{{ $field['description'] }}</div>
                                            @endif
                                            @break

                                        @default
                                            <label class="form-label fw-semibold" for="{{ $fieldId }}">
                                                {{ $field['label'] }}
                                                @if($isSystem)
                                                    <span class="badge bg-light text-dark ms-1">système</span>
                                                @endif
                                            </label>
                                            <input type="text" 
                                                   class="form-control settings-auto-save"
                                                   id="{{ $fieldId }}" 
                                                   data-key="{{ $fieldKey }}"
                                                   value="{{ $value }}"
                                                   {{ $disabled ? 'disabled' : '' }}>
                                            @if(isset($field['description']))
                                                <div class="form-text">{{ $field['description'] }}</div>
                                            @endif
                                    @endswitch
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    {{-- Toasts --}}
    <div class="toast-container position-fixed bottom-0 end-0 p-3">
        <div id="saveToast" class="toast align-items-center text-bg-success border-0" role="alert">
            <div class="d-flex">
                <div class="toast-body">✓ Paramètre sauvegardé</div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
            </div>
        </div>
    </div>

    <div class="toast-container position-fixed bottom-0 end-0 p-3">
        <div id="errorToast" class="toast align-items-center text-bg-danger border-0" role="alert">
            <div class="d-flex">
                <div class="toast-body">✗ Erreur lors de la sauvegarde</div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
            </div>
        </div>
    </div>
</x-admin-layout>