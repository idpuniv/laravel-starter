<x-admin-layout>
    <div class="container py-5">
        <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
            <x-breadcrumb></x-breadcrumb>

            <div class="d-flex align-items-center gap-2 mt-2 mt-sm-0">
                @can(\App\Permissions\PostPermissions::DELETE)
                    <button type="button"
                        class="btn btn-link text-danger icon-circle-xs text-decoration-none hover-bg-secondary-25 p-0 m-0"
                        data-bs-toggle="modal"
                        data-bs-target="#confirmModal"
                        data-url="{{ route('admin.posts.destroy', $post->id) }}"
                        data-method="DELETE"
                        title="{{ __('Supprimer') }}">
                        <i class="bi bi-trash"></i>
                    </button>
                @endcan
            </div>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <form action="{{ route('admin.posts.update', $post) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    @include('posts.partials.form')

                    <div class="d-flex justify-content-end gap-2 mt-4">
                        <a href="{{ route('admin.posts.index') }}" class="btn btn-outline-secondary px-4">{{ __('Annuler') }}</a>
                        <button type="submit" class="btn btn-primary px-4">{{ __('Mettre à jour') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-admin-layout>