<x-admin-layout>
    <div class="container py-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <x-breadcrumb></x-breadcrumb>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <form action="{{ route('admin.categories.store') }}" method="POST">
                    @csrf
                    @include('admin.categories.partials.form')

                    <div class="d-flex justify-content-end gap-2 mt-4">
                        <a href="{{ route('admin.categories.index') }}" class="btn btn-outline-secondary px-4">{{ __('Annuler') }}</a>
                        <button type="submit" class="btn btn-primary px-4">{{ __('Enregistrer') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-admin-layout>