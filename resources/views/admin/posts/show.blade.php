<x-admin-layout>
    <div class="container py-5">
        <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
            <x-breadcrumb></x-breadcrumb>
            <div class="d-flex align-items-center gap-2 mt-2 mt-sm-0">
                @can(\App\Permissions\PostPermissions::UPDATE)
                <a href="{{ route('admin.posts.edit', $post) }}"
                    class="btn btn-link text-body icon-circle-xs text-decoration-none hover-bg-secondary-25 p-0 m-0">
                    <i class="bi bi-pencil"></i>
                </a>
                @endcan

                @can(\App\Permissions\PostPermissions::DELETE)
                <button type="button"
                    class="btn btn-link text-danger icon-circle-xs text-decoration-none hover-bg-secondary-25 p-0 m-0"
                    data-bs-toggle="modal"
                    data-bs-target="#confirmModal"
                    data-url="{{ route('admin.posts.destroy', $post->id) }}"
                    data-method="DELETE">
                    <i class="bi bi-trash"></i>
                </button>
                @endcan
            </div>
        </div>
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <h2 class="h3 mb-3">{{ $post->title }}</h2>

                <div class="d-flex gap-3 mb-4 pb-3 border-bottom text-muted small">
                    <span>{{ $post->user->name ?? 'Auteur inconnu' }}</span>
                    <span>{{ $post->published_at ? $post->published_at->format('d/m/Y') : 'Non publié' }}</span>
                    <span>{{ number_format($post->views_count) }} vues</span>
                    @if($post->category)
                    <span>{{ $post->category->name }}</span>
                    @endif
                </div>

                @if($post->featured_image)
                <img src="{{ asset('storage/' . $post->featured_image) }}" class="img-fluid rounded mb-4" alt="{{ $post->title }}">
                @endif

                @if($post->summary)
                <div class="alert alert-light border mb-4">
                    <strong>Résumé :</strong>
                    <p class="mb-0">{{ $post->summary }}</p>
                </div>
                @endif

                <div class="mt-4">
                    <strong>Contenu :</strong>
                    <div class="mt-3 p-3 bg-light rounded">
                        {!! nl2br(e($post->content)) !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>