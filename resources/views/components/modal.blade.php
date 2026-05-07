{{-- Usage :

<x-modal name="customModal" title="Custom Footer">
    {{ __('This is a modal with a custom footer.') }}
    <x-slot name="footer">
        <button type="button" class="btn btn-danger">{{ __('Delete') }}</button>
    </x-slot>
</x-modal> --}}

{{-- Usage:
<x-modal name="customModal" title="Custom Footer">
    {{ __('This is a modal with a custom footer.') }}
    <x-slot name="footer">
        <button type="button" class="btn btn-danger">{{ __('Delete') }}</button>
    </x-slot>
</x-modal>
--}}

@props([
    'title' => '',
    'id' => 'myModal',
    'footer' => null,
])

<div class="modal fade" id="{{ $id }}" tabindex="-1" aria-labelledby="{{ $id }}Label" aria-hidden="true">
    <div {{ $attributes->merge(['class' => 'modal-dialog']) }}>
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="{{ $id }}Label">{{ $title }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="{{ __('Close') }}"></button>
            </div>
            <div class="modal-body">
                {{ $slot }}
            </div>
            @if($footer)
                <div {{ $footer->attributes->merge(['class' => 'modal-footer']) }}>
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">{{ __('Fermer') }}</button>
                    {{ $footer }}
                </div>
            @endif
        </div>
    </div>
</div>