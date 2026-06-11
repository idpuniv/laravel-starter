@section('title', __('Créer un compte utilisateur'))

<x-admin-layout>
<div class="container py-4">

    {{-- Header --}}
    <div class="d-flex align-items-center gap-2 mb-4">
        <a href="{{ route('admin.people.show', $person->id) }}"
           class="btn btn-link text-body icon-circle-xs text-decoration-none hover-bg-secondary-25 p-0 m-0">
            <i class="bi bi-arrow-left"></i>
        </a>
        <h1 class="h5 mb-0 fw-semibold">{{ __('Créer un compte utilisateur') }}</h1>
    </div>

    {{-- Person summary --}}
    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-body p-4">
            <div class="d-flex align-items-center gap-3 flex-wrap">

                {{-- Avatar --}}
                <div class="rounded-circle bg-primary-subtle border border-primary-subtle d-flex align-items-center justify-content-center flex-shrink-0 overflow-hidden"
                     style="width:56px;height:56px;font-size:1.4rem;">
                    @if($person->photo_url)
                        <img src="{{ $person->photo_url }}"
                             alt="{{ $person->first_name }}"
                             class="w-100 h-100 object-fit-cover">
                    @else
                        <i class="bi bi-person text-primary"></i>
                    @endif
                </div>

                {{-- Info --}}
                <div class="row g-3 flex-grow-1">
                    <div class="col-md-3">
                        <p class="text-body-secondary small text-uppercase fw-medium mb-1 d-flex align-items-center gap-1">
                            <i class="bi bi-person"></i> {{ __('Nom complet') }}
                        </p>
                        <p class="fw-semibold mb-0">
                            {{ $person->first_name }} {{ $person->last_name }}
                        </p>
                    </div>

                    <div class="col-md-3">
                        <p class="text-body-secondary small text-uppercase fw-medium mb-1 d-flex align-items-center gap-1">
                            <i class="bi bi-telephone"></i> {{ __('Téléphone') }}
                        </p>
                        <p class="fw-semibold mb-0">
                            {{ $person->full_phone ?: '—' }}
                        </p>
                    </div>

                    <div class="col-md-3">
                        <p class="text-body-secondary small text-uppercase fw-medium mb-1 d-flex align-items-center gap-1">
                            <i class="bi bi-geo-alt"></i> {{ __('Pays') }}
                        </p>
                        <p class="fw-semibold mb-0">
                            {{ $person->country?->name ?? '—' }}
                        </p>
                    </div>

                    <div class="col-md-3">
                        <p class="text-body-secondary small text-uppercase fw-medium mb-1 d-flex align-items-center gap-1">
                            <i class="bi bi-calendar3"></i> {{ __('Inscrit le') }}
                        </p>
                        <p class="fw-semibold mb-0">
                            {{ $person->created_at->format('d/m/Y') }}
                        </p>
                    </div>
                </div>

            </div>
        </div>
    </div>

    {{-- Account form --}}
    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-header bg-transparent border-bottom px-4 pt-4 pb-3">
            <h6 class="mb-0 fw-semibold d-flex align-items-center gap-2">
                <i class="bi bi-person-plus text-primary"></i>
                {{ __('Informations du compte') }}
            </h6>
        </div>
        <div class="card-body p-4">
            <form method="POST" action="{{ route('admin.users.store') }}">
                @csrf
                @isset($person)
                    <input type="hidden" name="person_id" value="{{ $person->id }}">
                @endisset

                @include('admin.users.partials.form')

                <div class="d-flex justify-content-end gap-2 mt-4">
                    <a href="{{ route('admin.people.show', $person->id) }}"
                       class="btn btn-sm btn-outline-secondary">
                        {{ __('Annuler') }}
                    </a>
                    <button type="submit" class="btn btn-sm btn-primary">
                        <i class="bi bi-check-lg me-1"></i>{{ __('Créer le compte') }}
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>
</x-admin-layout>