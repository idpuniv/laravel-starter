@section('title', 'Modifier une équipe')

<x-admin-layout>

<div class="container py-4">

    <h3 class="mb-4">Modifier une équipe</h3>

    <form action="{{ route('admin.teams.update', $team->id) }}" method="POST">
        @csrf
        @method('PUT')

        @include('admin.teams.partials.form')

        <div class="d-flex justify-content-end gap-2 mt-4">

            <a href="{{ route('admin.teams.index') }}"
               class="btn btn-outline-secondary">
                Retour
            </a>

            <button type="submit" class="btn btn-primary">
                Mettre à jour
            </button>

        </div>

    </form>

</div>

</x-admin-layout>