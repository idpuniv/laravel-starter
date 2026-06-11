@extends($layout)

@section('content')
<div class="container d-flex flex-column align-items-center justify-content-center h-100">
    <div class="text-center">
        <h1 class="display-1 text-muted">500</h1>
        <h3>{{ __('Erreur interne') }}</h3>
        <p class="text-muted">{{ __('Une erreur technique s\'est produite') }}.</p>
        <a href="{{ $home }}" class="btn btn-primary">{{ __('Retour') }}</a>
    </div>
</div>
@endsection