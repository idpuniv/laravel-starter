<x-admin-layout>
    <div class="container py-5">
        <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
            <x-breadcrumb></x-breadcrumb>
            <div class="d-flex align-items-center gap-2 mt-2 mt-sm-0">
                @can(\App\Permissions\CategoryPermissions::UPDATE)
                    <a href="{{ route('admin.categories.edit', $category) }}"
                        class="btn btn-link text-body icon-circle-xs text-decoration-none hover-bg-secondary-25 p-0 m-0">
                        <i class="bi bi-pencil"></i>
                    </a>
                @endcan

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
                <h2 class="h3 mb-3">{{ $category->name }}</h2>
                
                <div class="d-flex gap-3 mb-4 pb-3 border-bottom text-muted small">
                    <span>{{ __('Slug') }} : <code>{{ $category->slug }}</code></span>
                    @if($category->parent)
                        <span>{{ __('Parent') }} : {{ $category->parent->name }}</span>
                    @endif
                    <span>{{ __('Créé le') }} : {{ $category->created_at->format('d/m/Y H:i') }}</span>
                </div>
                
                @if($category->description)
                    <div class="alert alert-light border mb-4">
                        <strong>{{ __('Description') }} :</strong>
                        <p class="mb-0">{{ $category->description }}</p>
                    </div>
                @endif
                
                @if($category->children->count())
                    <div class="mt-4">
                        <strong>{{ __('Sous-catégories') }} :</strong>
                        <ul class="mt-2">
                            @foreach($category->children as $child)
                                <li>{{ $child->name }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-admin-layout>