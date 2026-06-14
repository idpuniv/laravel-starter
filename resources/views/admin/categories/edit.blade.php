<x-admin-layout>
    <div class="container py-5">
        <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
            <x-breadcrumb></x-breadcrumb>
            <div class="d-flex align-items-center gap-2 mt-2 mt-sm-0">
                @can(\App\Permissions\CategoryPermissions::DELETE)
                    <button type="button"
                        class="btn btn-link text-danger icon-circle-xs text-decoration-none hover-bg-secondary-25 p-0 m-0"
                        data-bs-toggle="modal"
                        data-bs-target="#confirmModal"
                        data-url="{{ route('admin.categories.destroy', $category) }}"
                        data-method="DELETE">
                        <i class="bi bi-trash"></i>
                    </button>
                @endcan
            </div>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <form action="{{ route('admin.categories.update', $category) }}" method="POST">
                    @csrf
                    @method('PUT')
                    @include('admin.categories.partials.form')
                    
                    <div class="d-flex justify-content-end gap-2 mt-4">
                        <a href="{{ route('admin.categories.index') }}" class="btn btn-outline-secondary px-4">{{ __('Annuler') }}</a>
                        <button type="submit" class="btn btn-primary px-4">{{ __('Mettre à jour') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-admin-layout>