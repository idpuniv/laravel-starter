
@section('title', 'Modifier une personne')

<x-admin-layout>

<div class="container py-4">

    <div class="card">
        <div class="card-body">

            <form method="POST"
                  action="{{ route('admin.people.update', $person->id) }}">
                @csrf
                @method('PUT')

                @include('admin.people.partials.form')

                <div class="mt-4 d-flex justify-content-end gap-2">
                    <a href="{{ route('admin.people.index') }}"
                       class="btn btn-outline-secondary">
                        Annuler
                    </a>

                    <button type="submit" class="btn btn-primary">
                        Mettre à jour
                    </button>
                </div>

            </form>

        </div>
    </div>

</div>
</x-admin-layout>