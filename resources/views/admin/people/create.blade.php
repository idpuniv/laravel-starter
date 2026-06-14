@section('title', 'Ajouter une personne')
<x-admin-layout>

    <div class="container py-4">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <x-breadcrumb></x-breadcrumb>
            <div class="d-flex align-items-center gap-2">
                @can(\App\Permissions\PersonPermissions::DELETE)
                <button type="button"
                    class="btn btn-link text-danger icon-circle-xs text-decoration-none hover-bg-secondary-25 p-0 m-0"
                    data-bs-toggle="modal"
                    data-bs-target="#confirmModal{{ $person->id }}"
                    title="{{ __('Supprimer') }}">
                    <i class="bi bi-trash"></i>
                </button>
                @endcan
            </div>
        </div>


        <div class="card">
            <div class="card-body">

                <form method="POST" action="{{ route('admin.people.store') }}">
                    @csrf

                    @include('admin.people.partials.form')

                    <div class="mt-4 d-flex justify-content-end gap-2">
                        <a href="{{ route('admin.people.index') }}"
                            class="btn btn-outline-secondary">
                            Annuler
                        </a>

                        <button type="submit" class="btn btn-primary">
                            Enregistrer
                        </button>
                    </div>

                </form>

            </div>
        </div>

    </div>
</x-admin-layout>