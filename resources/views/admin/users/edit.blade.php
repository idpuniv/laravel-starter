@section('title', 'Modifier un utilisateur')

<x-admin-layout>

<div class="container py-4">

    <h3>Modifier l'utilisateur</h3>

    <form method="POST"
          action="{{ route('admin.users.update', $user->id) }}">
        @csrf
        @method('PUT')

        @include('admin.users.partials.form')

        <div class="d-flex justify-content-end gap-2 mt-4">

            <a href="{{ route('admin.users.index') }}"
               class="btn btn-outline-secondary">
                Retour
            </a>

            <button class="btn btn-primary">
                Mettre à jour
            </button>

        </div>

    </form>

</div>

</x-admin-layout>