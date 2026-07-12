<?php

use App\Models\Person;
use App\Enums\UserStatus;
use App\Roles\Roles;
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
    protected string $model = Person::class;
    
    /**
     * Array of database columns or relation attributes that can be searched.
     * Supports dot notation for relationships (e.g., 'user.email').
     *
     * @var array
     */
    public array $searchable = ['last_name', 'first_name', 'phone', 'user.email'];

    /**
     * Model-specific search hook automatically resolved by the WithDataTable trait.
     * Configures default constraints such as eager loading to eliminate N+1 query issues.
     *
     * @param Builder $query
     * @return Builder
     */
    public function searchPeople(Builder $query): Builder
    {
        return $query->with(['user', 'user.roles']);
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
            switch ($this->statusFilter) {
                case 'with_account':    
                    $query->has('user'); 
                    break;
                case 'without_account': 
                    $query->doesntHave('user'); 
                    break;
                case UserStatus::ACTIVE->value:
                case UserStatus::INACTIVE->value:
                case UserStatus::BANNED->value:
                    $query->whereHas('user', fn($q) => $q->where('status', $this->statusFilter));
                    break;
            }
        }

        // Apply role-based constraints if a role filter is selected
        if ($this->roleFilter) {
            switch ($this->roleFilter) {
                case Roles::ADMIN:
                case Roles::ROOT:
                    $query->whereHas('user', fn($q) => $q->role($this->roleFilter));
                    break;
                case 'user':
                    $query->whereHas('user', fn($q) => $q->doesntHave('roles'));
                    break;
            }
        }

        return $query;
    }

    /**
     * Define the view payload data passed to the frontend rendering layout.
     *
     * @return array
     */
    public function with(): array
    {
        return [
            'users' => $this->getData(),
        ];
    }
};

?>

<div>
    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h5 fw-semibold m-0">{{ __('Liste des personnes') }}</h1>
        @can(\App\Permissions\PersonPermissions::CREATE)
            <a href="{{ route('admin.people.create') }}" class="btn btn-sm btn-primary">
                <i class="bi bi-plus-lg me-1"></i>{{ __('Créer') }}
            </a>
        @endcan
    </div>

    @php
        $activeFiltersCount = collect([$search, $statusFilter, $roleFilter])->filter()->count();
    @endphp

    {{-- Filters — DESKTOP (md et plus) --}}
    <div class="card border-0 shadow-sm rounded-4 mb-4 d-none d-md-block">
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
                               placeholder="{{ __('Nom, prénom, téléphone ou email...') }}">
                    </div>
                </div>

                <div class="col-md-2">
                    <select class="form-select form-select-sm" wire:model.live="statusFilter">
                        <option value="">{{ __('Tous les statuts') }}</option>
                        <option value="with_account">{{ __('Avec compte') }}</option>
                        <option value="without_account">{{ __('Sans compte') }}</option>
                        <option value="{{ UserStatus::ACTIVE->value }}">{{ UserStatus::ACTIVE->label() }}</option>
                        <option value="{{ UserStatus::INACTIVE->value }}">{{ UserStatus::INACTIVE->label() }}</option>
                        <option value="{{ UserStatus::BANNED->value }}">{{ UserStatus::BANNED->label() }}</option>
                    </select>
                </div>

                <div class="col-md-2">
                    <select class="form-select form-select-sm" wire:model.live="roleFilter">
                        <option value="">{{ __('Tous les rôles') }}</option>
                        <option value="{{ Roles::ADMIN }}">{{ __('Admin') }}</option>
                        <option value="{{ Roles::ROOT }}">{{ __('Root') }}</option>
                        <option value="user">{{ __('Utilisateur') }}</option>
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
                                    <span class="text-body-secondary">({{ $users->count() }})</span>
                                </button>
                            </li>
                            <li>
                                <button type="button" class="dropdown-item small" wire:click="exportPDF(true)">
                                    <i class="bi bi-filetype-pdf text-danger me-2"></i>
                                    {{ __('Tous les résultats') }}
                                    <span class="text-body-secondary">({{ $users->total() }})</span>
                                </button>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <span class="dropdown-header text-body-secondary small">Excel</span>
                            </li>
                            <li>
                                <button type="button" class="dropdown-item small" wire:click="exportExcel(false)">
                                    <i class="bi bi-filetype-xlsx text-success me-2"></i>
                                    {{ __('Cette page') }}
                                    <span class="text-body-secondary">({{ $users->count() }})</span>
                                </button>
                            </li>
                            <li>
                                <button type="button" class="dropdown-item small" wire:click="exportExcel(true)">
                                    <i class="bi bi-filetype-xlsx text-success me-2"></i>
                                    {{ __('Tous les résultats') }}
                                    <span class="text-body-secondary">({{ $users->total() }})</span>
                                </button>
                            </li>
                        </ul>
                    </div>
                </div>

            </div>
        </div>
    </div>

    {{-- Filters — MOBILE (moins de md) : recherche + bouton offcanvas --}}
    <div class="d-flex d-md-none gap-2 mb-4">
        <div class="input-group input-group-sm flex-grow-1">
            <span class="input-group-text bg-body-tertiary border-end-0">
                <i class="bi bi-search text-body-secondary"></i>
            </span>
            <input type="text"
                   wire:model.live.debounce.300ms="search"
                   class="form-control form-control-sm bg-body-tertiary border-start-0 ps-0"
                   placeholder="{{ __('Rechercher...') }}">
        </div>

        <button type="button"
                class="btn btn-sm btn-outline-secondary position-relative flex-shrink-0"
                data-bs-toggle="offcanvas"
                data-bs-target="#mobileFiltersOffcanvas">
            <i class="bi bi-sliders"></i>
            @if($activeFiltersCount > 0)
                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-primary"
                      style="font-size: .6rem;">
                    {{ $activeFiltersCount }}
                </span>
            @endif
        </button>
    </div>

    {{-- Offcanvas mobile : statut, rôle, perPage, export --}}
    <div class="offcanvas offcanvas-bottom" tabindex="-1" id="mobileFiltersOffcanvas" style="height: auto; max-height: 85vh; border-radius: 20px 20px 0 0;">
        <div class="offcanvas-header border-bottom">
            <h5 class="offcanvas-title fw-semibold">{{ __('Filtres') }}</h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
        </div>
        <div class="offcanvas-body">

            <label class="form-label small text-body-secondary fw-medium">{{ __('Statut') }}</label>
            <select class="form-select mb-3" wire:model.live="statusFilter">
                <option value="">{{ __('Tous les statuts') }}</option>
                <option value="with_account">{{ __('Avec compte') }}</option>
                <option value="without_account">{{ __('Sans compte') }}</option>
                <option value="{{ UserStatus::ACTIVE->value }}">{{ UserStatus::ACTIVE->label() }}</option>
                <option value="{{ UserStatus::INACTIVE->value }}">{{ UserStatus::INACTIVE->label() }}</option>
                <option value="{{ UserStatus::BANNED->value }}">{{ UserStatus::BANNED->label() }}</option>
            </select>

            <label class="form-label small text-body-secondary fw-medium">{{ __('Rôle') }}</label>
            <select class="form-select mb-3" wire:model.live="roleFilter">
                <option value="">{{ __('Tous les rôles') }}</option>
                <option value="{{ Roles::ADMIN }}">{{ __('Admin') }}</option>
                <option value="{{ Roles::ROOT }}">{{ __('Root') }}</option>
                <option value="user">{{ __('Utilisateur') }}</option>
            </select>

            <label class="form-label small text-body-secondary fw-medium">{{ __('Résultats par page') }}</label>
            <select class="form-select mb-4" wire:model.live="perPage">
                <option value="10">10</option>
                <option value="15">15</option>
                <option value="25">25</option>
                <option value="50">50</option>
                <option value="100">100</option>
            </select>

            <div class="d-flex gap-2 mb-3">
                <button type="button" class="btn btn-outline-secondary flex-fill" wire:click="resetFilters">
                    <i class="bi bi-x-circle me-1"></i>{{ __('Effacer') }}
                </button>
                <button type="button" class="btn btn-primary flex-fill" data-bs-dismiss="offcanvas">
                    {{ __('Appliquer') }}
                </button>
            </div>

            <hr>

            <label class="form-label small text-body-secondary fw-medium">{{ __('Exporter') }}</label>
            <div class="d-flex flex-column gap-2">
                <button type="button" class="btn btn-outline-secondary d-flex align-items-center justify-content-between" wire:click="exportPDF(false)">
                    <span><i class="bi bi-filetype-pdf text-danger me-2"></i>{{ __('PDF — cette page') }}</span>
                    <span class="text-body-secondary small">({{ $users->count() }})</span>
                </button>
                <button type="button" class="btn btn-outline-secondary d-flex align-items-center justify-content-between" wire:click="exportPDF(true)">
                    <span><i class="bi bi-filetype-pdf text-danger me-2"></i>{{ __('PDF — tous les résultats') }}</span>
                    <span class="text-body-secondary small">({{ $users->total() }})</span>
                </button>
                <button type="button" class="btn btn-outline-secondary d-flex align-items-center justify-content-between" wire:click="exportExcel(false)">
                    <span><i class="bi bi-filetype-xlsx text-success me-2"></i>{{ __('Excel — cette page') }}</span>
                    <span class="text-body-secondary small">({{ $users->count() }})</span>
                </button>
                <button type="button" class="btn btn-outline-secondary d-flex align-items-center justify-content-between" wire:click="exportExcel(true)">
                    <span><i class="bi bi-filetype-xlsx text-success me-2"></i>{{ __('Excel — tous les résultats') }}</span>
                    <span class="text-body-secondary small">({{ $users->total() }})</span>
                </button>
            </div>

        </div>
    </div>

    {{-- Active filters --}}
    @if($search || $statusFilter || $roleFilter)
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
                    @switch($statusFilter)
                        @case('with_account')    {{ __('Avec compte') }}             @break
                        @case('without_account') {{ __('Sans compte') }}             @break
                        @case(UserStatus::ACTIVE->value)    {{ UserStatus::ACTIVE->label() }} @break
                        @case(UserStatus::INACTIVE->value)  {{ UserStatus::INACTIVE->label() }} @break
                        @case(UserStatus::BANNED->value)    {{ UserStatus::BANNED->label() }} @break
                    @endswitch
                    <button type="button" class="btn btn-link p-0 m-0 text-body-secondary lh-1" wire:click="$set('statusFilter', '')">
                        <i class="bi bi-x"></i>
                    </button>
                </span>
            @endif

            @if($roleFilter)
                <span class="badge rounded-pill border text-body fw-normal d-inline-flex align-items-center gap-1 px-2 py-1">
                    <i class="bi bi-shield text-body-secondary" style="font-size:.7rem"></i>
                    @switch($roleFilter)
                        @case(Roles::ADMIN) {{ __('Admin') }}       @break
                        @case(Roles::ROOT)  {{ __('Root') }}        @break
                        @case('user')       {{ __('Utilisateur') }} @break
                    @endswitch
                    <button type="button" class="btn btn-link p-0 m-0 text-body-secondary lh-1" wire:click="$set('roleFilter', '')">
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
            'first' => $users->firstItem(),
            'last'  => $users->lastItem(),
            'total' => $users->total(),
        ]) }}
    </div>

    {{-- Table --}}
    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="border-bottom">
                    <tr>
                        <th class="ps-4 text-body-secondary fw-medium small text-nowrap">#</th>
                        <th class="text-body-secondary fw-medium small text-nowrap">{{ __('Personne') }}</th>
                        <th class="text-body-secondary fw-medium small text-nowrap">{{ __('Téléphone') }}</th>
                        <th class="text-body-secondary fw-medium small text-nowrap">{{ __('Email') }}</th>
                        <th class="text-body-secondary fw-medium small text-nowrap">{{ __('Rôle') }}</th>
                        <th class="text-body-secondary fw-medium small text-nowrap">{{ __('Date') }}</th>
                        <th class="text-body-secondary fw-medium small text-nowrap text-center">{{ __('Actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $person)
                        <tr>
                            {{-- # --}}
                            <td class="ps-4 text-body-secondary small">
                                {{ $users->firstItem() + $loop->index }}
                            </td>

                            {{-- Name --}}
                             <td>
    <div class="d-flex align-items-center gap-2">
        <div class="position-relative flex-shrink-0" style="width:32px;height:32px;" 
             title="{{ $person->user && $person->user->last_logged_at ? __('Activité : :time', ['time' => time_ago_short($person->user->last_logged_at)]) : '' }}">
            
            <div class="rounded-circle bg-primary-subtle d-flex align-items-center justify-content-center w-100 h-100"
                 style="font-size:.8rem;">
                <span class="text-primary fw-semibold">
                    {{ strtoupper(substr($person->first_name, 0, 1)) }}{{ strtoupper(substr($person->last_name, 0, 1)) }}
                </span>
            </div>

            {{-- Pastille en ligne — S'affiche uniquement si le user est connecté --}}
            @if($person->user && $person->user->is_logged)
                <span class="person-online-dot position-absolute rounded-circle"
                      style="width:9px;height:9px;bottom:-1px;right:-1px;background:#28a745;border:2px solid var(--bs-body-bg,#fff);"
                      title="{{ __('En ligne') }}"></span>
            @endif
        </div>
        <div>
            <div class="fw-medium small text-nowrap">
                {{ $person->last_name }} {{ $person->first_name }}
            </div>
            @if(!$person->user)
                <span class="badge text-bg-secondary rounded-pill" style="font-size:.65rem">
                    {{ __('Sans compte') }}
                </span>
            @endif
        </div>
    </div>
</td>

                            {{-- Phone --}}
                            <td class="small text-body-secondary text-nowrap">
                                {{ $person->full_phone ?: '—' }}
                            </td>

                            {{-- Email --}}
                            <td class="small text-nowrap">
                                @if($person->user)
                                    <a href="mailto:{{ $person->user->email }}"
                                       class="text-decoration-none text-body-secondary">
                                        {{ $person->user->email }}
                                    </a>
                                @else
                                    <span class="text-body-tertiary">—</span>
                                @endif
                            </td>

                            {{-- Roles --}}
                            <td>
                                @if(!$person->user)
                                    <span class="text-body-tertiary small">—</span>
                                @else
                                    @php $roles = $person->user->getRoleNames(); @endphp
                                    @if($roles->isEmpty())
                                        <span class="text-body-tertiary small fst-italic">{{ __('Aucun rôle') }}</span>
                                    @else
                                        <div class="d-flex flex-nowrap gap-1">
                                            @foreach($roles->take(2) as $roleName)
                                                <span class="badge rounded-pill bg-primary-subtle text-primary border border-primary-subtle text-nowrap"
                                                      style="font-size:.7rem">
                                                    {{ $roleName }}
                                                </span>
                                            @endforeach
                                            @if($roles->count() > 2)
                                                <span class="badge rounded-pill bg-body-secondary text-body-secondary"
                                                      style="font-size:.7rem">
                                                    +{{ $roles->count() - 2 }}
                                                </span>
                                            @endif
                                        </div>
                                    @endif
                                @endif
                            </td>

                            {{-- Date --}}
                            <td class="small text-body-secondary text-nowrap">
                                {{ $person->created_at->format('d/m/Y') }}
                                <div class="text-body-tertiary" style="font-size:.7rem">
                                    {{ $person->created_at->format('H:i') }}
                                </div>
                            </td>

                            {{-- Actions --}}
                            <td>
                                <div class="d-flex align-items-center justify-content-center gap-1">
                                    @can(\App\Permissions\UserPermissions::VIEW)
                                        <a href="{{ route('admin.people.show', $person->id) }}"
                                           class="btn btn-link text-body icon-circle-xs text-decoration-none hover-bg-secondary-25 p-0 m-0"
                                           title="{{ __('Voir') }}">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                    @endcan

                                    @can(\App\Permissions\UserPermissions::UPDATE)
                                        <a href="{{ route('admin.people.edit', $person->id) }}"
                                           class="btn btn-link text-body icon-circle-xs text-decoration-none hover-bg-secondary-25 p-0 m-0"
                                           title="{{ __('Modifier') }}">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                    @endcan

                                    @can(\App\Permissions\UserPermissions::DELETE)
                                        <button type="button"
                                                class="btn btn-link text-danger icon-circle-xs text-decoration-none hover-bg-secondary-25 p-0 m-0"
                                                data-bs-toggle="modal"
                                                data-bs-target="#confirmModal"
                                                data-url="{{ route('admin.people.destroy', $person->id) }}"
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
                            <td colspan="7" class="text-center py-5 text-body-secondary">
                                <i class="bi bi-inbox fs-3 d-block mb-2 opacity-50"></i>
                                {{ __('Aucune personne trouvée.') }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Pagination --}}
    @if($users && $users->hasPages())
        <livewire:pagination
            :current-page="$users->currentPage()"
            :last-page="$users->lastPage()"
            :total="$users->total()"
            :first-item="$users->firstItem()"
            :last-item="$users->lastItem()"
            :has-pages="$users->hasPages()"
            :on-first-page="$users->onFirstPage()"
            :has-more-pages="$users->hasMorePages()" />
    @endif

</div>