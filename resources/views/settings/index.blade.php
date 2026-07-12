@section('title', 'Paramètres')
<x-admin-layout>
    @vite('resources/js/pages/settings.js')

    <div class="container py-4">
        {{-- Header --}}
        <div class="d-flex justify-content-between align-items-center mb-4 settings-cards-wrapper">
            <div class="d-flex align-items-center gap-3">
                <div class="rounded-3 bg-primary-subtle d-flex align-items-center justify-content-center flex-shrink-0"
                     style="width:48px;height:48px;">
                    <i class="bi bi-sliders text-primary fs-5"></i>
                </div>
                <div>
                    <h1 class="h4 fw-bold mb-0">{{ __('Paramètres') }}</h1>
                    <p class="text-body-secondary small mb-0">{{ __("Gérez les paramètres de l'application") }}</p>
                </div>
            </div>
            <span class="badge text-bg-secondary rounded-pill px-3 py-2">
                {{ trans_choice(':count paramètre|:count paramètres', count($settings), ['count' => count($settings)]) }}
            </span>
        </div>

        {{-- Groups --}}
        <div class="d-flex flex-column gap-4 settings-cards-wrapper">
            @foreach ($groups as $groupKey => $group)
                <div class="card border-0 shadow-sm rounded-4">
                    {{-- Group header --}}
                    <div class="card-header bg-transparent border-0 rounded-4 py-3 px-4 d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center gap-3">
                            <div class="rounded-circle bg-body-secondary d-flex align-items-center justify-content-center flex-shrink-0"
                                 style="width:40px;height:40px;">
                                <i class="{{ $group['icon'] }} text-body-secondary"></i>
                            </div>
                            <div>
                                <div class="fw-semibold">{{ $group['label'] }}</div>
                                @if(isset($group['description']))
                                    <div class="text-body-secondary small">{{ $group['description'] }}</div>
                                @endif
                            </div>
                        </div>
                        <span class="badge text-bg-light text-body-secondary">
                            {{ trans_choice(':count champ|:count champs', count($group['fields']), ['count' => count($group['fields'])]) }}
                        </span>
                    </div>

                    {{-- Group body --}}
                    <div class="card-body border-top pt-4">
                        @foreach ($group['fields'] as $fieldKey)
                            @php
                                $field = $fields[$fieldKey];
                                $value = $settings[$fieldKey] ?? null;
                                $isSystem = str_starts_with($fieldKey, 'system.');
                                $disabled = $isSystem && !Auth::user()?->can(\App\Permissions\SystemPermissions::EDIT_SETTINGS);
                                $fieldId = 'field_' . Str::slug($fieldKey);
                            @endphp

                            <div class="mb-4 pb-4 {{ !$loop->last ? 'border-bottom' : 'mb-0 pb-0' }}">
                                @switch($field['type'])
                                    @case('boolean')
                                        <div class="d-flex align-items-center justify-content-between gap-3">
                                            <label class="form-check-label mb-0" for="{{ $fieldId }}">
                                                <span class="fw-semibold">{{ $field['label'] }}</span>
                                                @if($isSystem)
                                                    <span class="badge text-bg-light text-body-secondary ms-1">
                                                        <i class="bi bi-lock-fill me-1"></i>{{ __('système') }}
                                                    </span>
                                                @endif
                                                @if(isset($field['description']))
                                                    <div class="form-text mt-0">{{ $field['description'] }}</div>
                                                @endif
                                            </label>
                                            <div class="form-check form-switch flex-shrink-0 mb-0">
                                                <input class="form-check-input settings-auto-save"
                                                       type="checkbox"
                                                       role="switch"
                                                       id="{{ $fieldId }}"
                                                       data-key="{{ $fieldKey }}"
                                                       {{ $value ? 'checked' : '' }}
                                                       {{ $disabled ? 'disabled' : '' }}>
                                            </div>
                                        </div>
                                        @break

                                    @case('select')
                                        <label class="form-label fw-semibold" for="{{ $fieldId }}">
                                            {{ $field['label'] }}
                                            @if($isSystem)
                                                <span class="badge text-bg-light text-body-secondary ms-1">
                                                    <i class="bi bi-lock-fill me-1"></i>{{ __('système') }}
                                                </span>
                                            @endif
                                        </label>
                                        <select class="form-select settings-auto-save settings-field-inline"
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
                                                <span class="badge text-bg-light text-body-secondary ms-1">
                                                    <i class="bi bi-lock-fill me-1"></i>{{ __('système') }}
                                                </span>
                                            @endif
                                        </label>
                                        <input type="number"
                                               class="form-control settings-auto-save settings-field-inline"
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
                                                <span class="badge text-bg-light text-body-secondary ms-1">
                                                    <i class="bi bi-lock-fill me-1"></i>{{ __('système') }}
                                                </span>
                                            @endif
                                        </label>
                                        <input type="text"
                                               class="form-control settings-auto-save settings-field-inline"
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
            @endforeach
        </div>
    </div>

    {{-- Toasts --}}
    <div class="toast-container position-fixed bottom-0 end-0 p-3">
        <div id="saveToast" class="toast align-items-center text-bg-success border-0" role="alert">
            <div class="d-flex">
                <div class="toast-body">
                    <i class="bi bi-check-circle-fill me-2"></i>{{ __('Paramètre sauvegardé') }}
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
            </div>
        </div>
    </div>

    <div class="toast-container position-fixed bottom-0 end-0 p-3">
        <div id="errorToast" class="toast align-items-center text-bg-danger border-0" role="alert">
            <div class="d-flex">
                <div class="toast-body">
                    <i class="bi bi-x-circle-fill me-2"></i>{{ __('Erreur lors de la sauvegarde') }}
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
            </div>
        </div>
    </div>

    <style>
        /* Fields look like plain text until double-clicked; scoped to avoid touching settings.js behavior. */
        .settings-field-inline:not(.is-editing) {
            border: 0;
            background-color: transparent;
            box-shadow: none;
            padding-left: 0;
            cursor: text;
        }

        select.settings-field-inline:not(.is-editing) {
            cursor: pointer;
        }

        /* Narrower reading width on large screens: 70% of the container, centered. */
        @media (min-width: 992px) {
            .settings-cards-wrapper {
                max-width: 70%;
                margin-inline: auto;
            }
        }
    </style>

    <script>
        // Reveals the normal Bootstrap field chrome on dblclick, hides it again on blur.
        // Isolated from settings.js: only listens to dblclick/blur, never touches
        // .settings-auto-save, data-key, or the auto-save request cycle.
        document.querySelectorAll('.settings-field-inline').forEach(function (field) {
            field.addEventListener('dblclick', function () {
                field.classList.add('is-editing');
                field.focus();

                if (field.tagName === 'INPUT') {
                    field.select();
                }
            });

            field.addEventListener('blur', function () {
                field.classList.remove('is-editing');
            });
        });
    </script>
</x-admin-layout>