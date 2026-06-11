@extends($layout)

@section('content')
<div class="container d-flex flex-column align-items-center justify-content-center h-100">
    <div class="text-center">
        <h1 class="display-1 text-muted">419</h1>

        <h3>{{ __('Page expirée') }}</h3>

        <p class="text-muted">
            {{ __('La page que vous recherchez a expiré.') }}
        </p>

        <a href="{{ $home }}" class="btn btn-primary">
            {{ __('Retour') }}
        </a>
    </div>
</div>
@endsection