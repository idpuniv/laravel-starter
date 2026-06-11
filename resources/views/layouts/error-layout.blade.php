@php
    if (auth('admin')->check()) {
        $layout = 'layouts.admin-layout';
        $home = route('admin.dashboard');
    } elseif (auth()->check()) {
        $layout = 'layouts.app-layout';
        $home = route('dashboard');
    } else {
        $layout = 'layouts.guest-layout';
        $home = url('/');
    }
@endphp

@extends($layout)

@section('content')
<div class="container py-5">
    <div class="text-center">
        <h1 class="display-1 text-muted">404</h1>
        <h3>Page non trouvée</h3>
        <p class="text-muted">La page que vous recherchez n'existe pas.</p>
        <a href="{{ $home }}" class="btn btn-primary">Retour</a>
    </div>
</div>
@endsection