@props([
'type' => 'success', // success, info, warning, danger
'message' => '',
'dismissible' => false
])

@php
$icons = [
'success' => 'bi-check-circle-fill',
'info' => 'bi-info-circle-fill',
'warning' => 'bi-exclamation-triangle-fill',
'danger' => 'bi-x-circle-fill',
];

$colors = [
'success' => 'success',
'info' => 'info',
'warning' => 'warning',
'danger' => 'danger',
];

$icon = $icons[$type] ?? $icons['success'];
$color = $colors[$type] ?? $colors['success'];
@endphp


<div class="d-flex align-items-center gap-2" {{ $attributes->merge(['class' => ' align-items-center gap-2']) }}>
    <div class="border-icon border-{{ $color }}">
        <i class="bi bi-check text-{{ $color }}"></i>
    </div>
    <span>{{ $message }}</span>
</div>

<style>
    .border-icon {
        width: 24px;
        height: 24px;
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border: 2px solid;
        font-size: 12px;
    }
</style>