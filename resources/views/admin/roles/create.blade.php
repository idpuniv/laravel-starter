@section('title', __('Créer un rôle'))

<x-admin-layout>

<div class="container py-4">

    <h3>{{ __('Créer un rôle') }}</h3>

    <form action="{{ route('admin.roles.store') }}" method="POST">
        @csrf

        @include('admin.roles.partials.form')

        <div class="d-flex justify-content-end gap-2">

            <a href="{{ route('admin.roles.index') }}"
               class="btn btn-outline-secondary">
                {{ __('Retour') }}
            </a>

            <button class="btn btn-primary">
                {{ __('Enregistrer') }}
            </button>

        </div>

    </form>

</div>

</x-admin-layout>