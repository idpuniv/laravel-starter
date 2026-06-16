

@props([
    'title'  => '',
    'id'     => 'myModal',
    'footer' => null,
    'size'   => '',   // modal-sm | modal-lg | modal-xl | ''
])

<div class="modal fade"
     id="{{ $id }}"
     tabindex="-1"
     aria-labelledby="{{ $id }}Label"
     aria-hidden="true">

    <div {{ $attributes->merge(['class' => trim('modal-dialog ' . $size)]) }}>
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title fw-semibold" id="{{ $id }}Label">
                    {{ $title }}
                </h5>
                
                <button type="button"
                        class="btn-close"
                        data-bs-dismiss="modal"
                        aria-label="{{ __('Fermer') }}">
                </button>
            </div>
            <div class="modal-body">
                {{ $slot }}
            </div>

            @if($footer)
                <div {{ $footer->attributes->merge(['class' => 'modal-footer']) }}>
                    <button type="button"
                            class="btn btn-sm btn-outline-secondary"
                            data-bs-dismiss="modal">
                        {{ __('Annuler') }}
                    </button>
                    {{ $footer }}
                </div>
            @endif

        </div>
    </div>
</div>  