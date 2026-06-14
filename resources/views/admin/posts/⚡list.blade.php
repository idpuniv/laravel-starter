<?php

namespace App\Livewire\Admin;

use App\Models\Post;
use App\Enums\PostStatus;
use App\Traits\WithDataTable;
use App\Traits\WithExport;
use Livewire\Component;
use Illuminate\Database\Eloquent\Builder;

new class extends Component {
    use WithDataTable, WithExport;

    /**
     * The target Eloquent model class associated with this data table.
     *
     * @var string
     */
    protected string $model = Post::class;

    /**
     * Array of database columns or relation attributes that can be searched.
     * Supports dot notation for relationships (e.g., 'user.name').
     *
     * @var array
     */
    public array $searchable = ['title', 'slug', 'content', 'user.name'];

    /**
     * Additional filter property for category (not defined in traits)
     *
     * @var string
     */
    public $categoryFilter = '';

    /**
     * Model-specific search hook automatically resolved by the WithDataTable trait.
     * Configures default constraints such as eager loading to eliminate N+1 query issues.
     *
     * @param Builder $query
     * @return Builder
     */
    public function searchPosts(Builder $query): Builder
    {
        return $query->with(['user', 'category', 'tags']);
    }

    /**
     * Apply specific domain and business logic filters to the query builder instance.
     *
     * @param Builder $query
     * @return Builder
     */
    public function applyFilters(Builder $query): Builder
    {
        // Apply status-based segmentation if a status filter is selected
        if ($this->statusFilter) {
            $query->where('status', $this->statusFilter);
        }

        // Apply category-based constraints if a category filter is selected
        if ($this->categoryFilter) {
            $query->where('category_id', $this->categoryFilter);
        }

        return $query;
    }

    /**
     * Reset all filters to their default empty state.
     *
     * @return void
     */
    public function resetFilters(): void
    {
        $this->reset(['search', 'statusFilter', 'categoryFilter', 'perPage']);
    }

    /**
     * Define the view payload data passed to the frontend rendering layout.
     *
     * @return array
     */
    public function with(): array
    {
        return [
            'posts' => $this->getData(),
            'categories' => \App\Models\Category::orderBy('name')->get(),
            'statusOptions' => PostStatus::cases(),
        ];
    }
};

?>

<div>
    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h5 fw-semibold m-0">{{ __('Liste des articles') }}</h1>
        @can(\App\Permissions\PostPermissions::CREATE)
        <a href="{{ route('admin.posts.create') }}" class="btn btn-sm btn-primary">
            <i class="bi bi-plus-lg me-1"></i>{{ __('Créer') }}
        </a>
        @endcan
    </div>

    {{-- Filters --}}
    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-body p-3">
            <div class="row g-2 align-items-center">

                <div class="col-md-4">
                    <div class="input-group input-group-sm">
                        <span class="input-group-text bg-body-tertiary border-end-0">
                            <i class="bi bi-search text-body-secondary"></i>
                        </span>
                        <input type="text"
                            wire:model.live.debounce.300ms="search"
                            class="form-control form-control-sm bg-body-tertiary border-start-0 ps-0"
                            placeholder="{{ __('Titre, contenu ou auteur...') }}">
                    </div>
                </div>

                <div class="col-md-2">
    <select class="form-select form-select-sm" wire:model.live="statusFilter">
        <option value="">{{ __('Tous les statuts') }}</option>
        @foreach($statusOptions as $status)
            <option value="{{ $status->value }}">{{ $status->label() }}</option>
        @endforeach
    </select>
</div>
                <div class="col-md-2">
                    <select class="form-select form-select-sm" wire:model.live="categoryFilter">
                        <option value="">{{ __('Toutes les catégories') }}</option>
                        @foreach($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-1">
                    <select class="form-select form-select-sm" wire:model.live="perPage">
                        <option value="10">10</option>
                        <option value="15">15</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                    </select>
                </div>

                <div class="col-md-3 d-flex gap-2 justify-content-md-end">
                    <button type="button"
                        class="btn btn-sm btn-link text-body-secondary text-decoration-none p-0"
                        wire:click="resetFilters"
                        title="{{ __('Effacer les filtres') }}">
                        <i class="bi bi-x-circle me-1"></i>{{ __('Effacer') }}
                    </button>

                    <div class="dropdown">
                        <button type="button"
                            class="btn btn-sm btn-outline-secondary dropdown-toggle"
                            data-bs-toggle="dropdown">
                            <i class="bi bi-download me-1"></i>{{ __('Exporter') }}
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 rounded-3">
                            <li>
                                <span class="dropdown-header text-body-secondary small">PDF</span>
                            </li>
                            <li>
                                <button type="button" class="dropdown-item small" wire:click="exportPDF(false)">
                                    <i class="bi bi-filetype-pdf text-danger me-2"></i>
                                    {{ __('Cette page') }}
                                    <span class="text-body-secondary">({{ $posts->count() }})</span>
                                </button>
                            </li>
                            <li>
                                <button type="button" class="dropdown-item small" wire:click="exportPDF(true)">
                                    <i class="bi bi-filetype-pdf text-danger me-2"></i>
                                    {{ __('Tous les résultats') }}
                                    <span class="text-body-secondary">({{ $posts->total() }})</span>
                                </button>
                            </li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li>
                                <span class="dropdown-header text-body-secondary small">Excel</span>
                            </li>
                            <li>
                                <button type="button" class="dropdown-item small" wire:click="exportExcel(false)">
                                    <i class="bi bi-filetype-xlsx text-success me-2"></i>
                                    {{ __('Cette page') }}
                                    <span class="text-body-secondary">({{ $posts->count() }})</span>
                                </button>
                            </li>
                            <li>
                                <button type="button" class="dropdown-item small" wire:click="exportExcel(true)">
                                    <i class="bi bi-filetype-xlsx text-success me-2"></i>
                                    {{ __('Tous les résultats') }}
                                    <span class="text-body-secondary">({{ $posts->total() }})</span>
                                </button>
                            </li>
                        </ul>
                    </div>
                </div>

            </div>
        </div>
    </div>

    {{-- Active filters --}}
    @if($search || $statusFilter || $categoryFilter)
    <div class="d-flex flex-wrap gap-2 align-items-center mb-3">
        <span class="text-body-secondary small">{{ __('Filtres :') }}</span>

        @if($search)
        <span class="badge rounded-pill border text-body fw-normal d-inline-flex align-items-center gap-1 px-2 py-1">
            <i class="bi bi-search text-body-secondary" style="font-size:.7rem"></i>
            {{ $search }}
            <button type="button" class="btn btn-link p-0 m-0 text-body-secondary lh-1" wire:click="$set('search', '')">
                <i class="bi bi-x"></i>
            </button>
        </span>
        @endif

        @if($statusFilter)
        <span class="badge rounded-pill border text-body fw-normal d-inline-flex align-items-center gap-1 px-2 py-1">
            <i class="bi bi-funnel text-body-secondary" style="font-size:.7rem"></i>
            @php
            $status = \App\Enums\PostStatus::tryFrom($statusFilter);
            @endphp
            {{ $status?->label() ?? $statusFilter }}
            <button type="button" class="btn btn-link p-0 m-0 text-body-secondary lh-1" wire:click="$set('statusFilter', '')">
                <i class="bi bi-x"></i>
            </button>
        </span>
        @endif

        @if($categoryFilter)
        <span class="badge rounded-pill border text-body fw-normal d-inline-flex align-items-center gap-1 px-2 py-1">
            <i class="bi bi-folder text-body-secondary" style="font-size:.7rem"></i>
            @php
            $category = \App\Models\Category::find($categoryFilter);
            @endphp
            {{ $category?->name ?? $categoryFilter }}
            <button type="button" class="btn btn-link p-0 m-0 text-body-secondary lh-1" wire:click="$set('categoryFilter', '')">
                <i class="bi bi-x"></i>
            </button>
        </span>
        @endif

        <button type="button"
            class="btn btn-link btn-sm text-danger p-0 text-decoration-none"
            wire:click="resetFilters">
            {{ __('Tout effacer') }}
        </button>
    </div>
    @endif

    {{-- Results count --}}
    <div class="text-body-secondary small mb-3">
        {{ __(':first–:last sur :total résultat(s)', [
            'first' => $posts->firstItem(),
            'last'  => $posts->lastItem(),
            'total' => $posts->total(),
        ]) }}
    </div>

    {{-- Table --}}
    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="border-bottom">
                    <tr>
                        <th class="ps-4 text-body-secondary fw-medium small">#</th>
                        <th class="text-body-secondary fw-medium small">{{ __('Titre') }}</th>
                        <th class="text-body-secondary fw-medium small">{{ __('Auteur') }}</th>
                        <th class="text-body-secondary fw-medium small">{{ __('Catégorie') }}</th>
                        <th class="text-body-secondary fw-medium small">{{ __('Statut') }}</th>
                        <th class="text-body-secondary fw-medium small">{{ __('Vues') }}</th>
                        <th class="text-body-secondary fw-medium small">{{ __('Date') }}</th>
                        <th class="text-body-secondary fw-medium small text-center">{{ __('Actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($posts as $post)
                    <tr>
                        <td class="ps-4 text-body-secondary small">
                            {{ $posts->firstItem() + $loop->index }}
                        </td>

                        <td>
                            <div class="fw-medium small">
                                {{ $post->title }}
                            </div>
                            <div class="text-body-tertiary" style="font-size:.7rem">
                                {{ $post->slug }}
                            </div>
                        </td>

                        <td class="small text-body-secondary">
                            {{ $post->user?->name ?? '—' }}
                        </td>

                        <td class="small">
                            @if($post->category)
                            <span class="badge rounded-pill bg-secondary-subtle text-secondary">
                                {{ $post->category->name }}
                            </span>
                            @else
                            <span class="text-body-tertiary">—</span>
                            @endif
                        </td>

                        <td>
                            @php
                            $statusColors = [
                            'draft' => 'warning',
                            'published' => 'success',
                            'archived' => 'danger',
                            ];
                            $color = $statusColors[$post->status->value] ?? 'secondary';
                            @endphp
                            <span class="badge rounded-pill bg-{{ $color }}-subtle text-{{ $color }}">
                                {{ $post->status->label() }}
                            </span>
                        </td>

                        <td class="small text-body-secondary">
                            {{ number_format($post->views_count) }}
                        </td>

                        <td class="small text-body-secondary">
                            {{ $post->published_at?->format('d/m/Y') ?: '—' }}
                            <div class="text-body-tertiary" style="font-size:.7rem">
                                {{ $post->created_at->format('d/m/Y H:i') }}
                            </div>
                        </td>

                        <td>
                            <div class="d-flex align-items-center justify-content-center gap-1">
                                @can(\App\Permissions\PostPermissions::VIEW)
                                <a href="{{ route('admin.posts.show', $post->id) }}"
                                    class="btn btn-link text-body icon-circle-xs text-decoration-none hover-bg-secondary-25 p-0 m-0"
                                    title="{{ __('Voir') }}">
                                    <i class="bi bi-eye"></i>
                                </a>
                                @endcan

                                @can(\App\Permissions\PostPermissions::UPDATE)
                                <a href="{{ route('admin.posts.edit', $post->id) }}"
                                    class="btn btn-link text-body icon-circle-xs text-decoration-none hover-bg-secondary-25 p-0 m-0"
                                    title="{{ __('Modifier') }}">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                @endcan

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
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-5 text-body-secondary">
                            <i class="bi bi-inbox fs-3 d-block mb-2 opacity-50"></i>
                            {{ __('Aucun article trouvé.') }}
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Pagination --}}
    @if($posts && $posts->hasPages())
    <livewire:pagination
        :current-page="$posts->currentPage()"
        :last-page="$posts->lastPage()"
        :total="$posts->total()"
        :first-item="$posts->firstItem()"
        :last-item="$posts->lastItem()"
        :has-pages="$posts->hasPages()"
        :on-first-page="$posts->onFirstPage()"
        :has-more-pages="$posts->hasMorePages()" />
    @endif

</div>