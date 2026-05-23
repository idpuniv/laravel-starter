@extends($layout)

@section('content')
<div class="container py-5">
    <div class="text-center">
        <h1 class="display-1 text-muted">500</h1>
        <h3>Erreur interne</h3>
        <p class="text-muted">Une erreur technique s'est produite.</p>
        <a href="{{ $home }}" class="btn btn-primary">Retour</a>
    </div>
</div>
@endsection