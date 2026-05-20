<?php

use App\Models\Person;
use App\Enums\Status;
use App\Roles\Roles;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Rap2hpoutre\FastExcel\FastExcel;

new class extends Component {
    use WithPagination;

    public $search = '';
    public $statusFilter = '';
    public $roleFilter = '';
    public $perPage = 15;

    protected $listeners = [
        'goToPage' => 'goToPage',
        'previousPage' => 'previousPage',
        'nextPage' => 'nextPage',
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingStatusFilter()
    {
        $this->resetPage();
    }

    public function updatingRoleFilter()
    {
        $this->resetPage();
    }

    public function updatingPerPage()
    {
        $this->resetPage();
    }

    public function search()
    {
        $op = DB::getDriverName() === 'pgsql' ? 'ILIKE' : 'LIKE';
        $searchTerm = '%' . $this->search . '%';

        return Person::with('user')->where(function ($query) use ($op, $searchTerm) {
            $query
                ->where('last_name', $op, $searchTerm)
                ->orWhere('first_name', $op, $searchTerm)
                ->orWhere('phone', $op, $searchTerm)
                ->orWhereHas('user', function ($q) use ($op, $searchTerm) {
                    $q->where('email', $op, $searchTerm);
                });
        });
    }

    public function filter($query)
    {
        if ($this->statusFilter) {
            switch ($this->statusFilter) {
                case 'with_account':
                    $query->has('user');
                    break;
                case 'without_account':
                    $query->doesntHave('user');
                    break;
                case Status::ACTIVE:
                    $query->whereHas('user', function ($q) {
                        $q->where('status', Status::ACTIVE);
                    });
                    break;
                case Status::INACTIVE:
                    $query->whereHas('user', function ($q) {
                        $q->where('status', Status::INACTIVE);
                    });
                    break;
                case 'banned':
                    $query->whereHas('user', function ($q) {
                        $q->where('status', 'banned');
                    });
                    break;
            }
        }

        if ($this->roleFilter) {
            switch ($this->roleFilter) {
                case Roles::ADMIN:
                    $query->whereHas('user', function ($q) {
                        $q->role(Roles::ADMIN);
                    });
                    break;
                case Roles::ROOT:
                    $query->whereHas('user', function ($q) {
                        $q->role(Roles::ROOT);
                    });
                    break;
                case 'user':
                    $query->whereHas('user', function ($q) {
                        $q->doesntHave('roles');
                    });
                    break;
            }
        }

        return $query;
    }

    public function getUsers()
    {
        $query = $this->search();
        $query = $this->filter($query);
        return $query->latest()->paginate($this->perPage);
    }

    public function getCurrentPageData()
    {
        return $this->getUsers();
    }

    public function getAllMatchingData()
    {
        $query = $this->search();
        $query = $this->filter($query);
        return $query->latest()->get();
    }

    public function goToPage($page)
    {
        $this->setPage($page);
    }

    public function previousPage()
    {
        $this->setPage(max(1, $this->getPage() - 1));
    }

    public function nextPage()
    {
        $this->setPage($this->getPage() + 1);
    }

    public function resetFilters()
    {
        $this->search = '';
        $this->statusFilter = '';
        $this->roleFilter = '';
        $this->perPage = 15;
        $this->resetPage();
    }

    public function exportExcel($exportAll = false)
    {
        if ($exportAll) {
            $data = $this->getAllMatchingData();
            $filename = 'utilisateurs_tous_' . date('Y-m-d_His') . '.xlsx';
        } else {
            $data = $this->getCurrentPageData();
            $filename = 'utilisateurs_page_' . $this->getPage() . '_' . date('Y-m-d_His') . '.xlsx';
        }

        if ($data instanceof \Illuminate\Pagination\LengthAwarePaginator) {
            $data = $data->getCollection();
        }

        return response()->streamDownload(function () use ($data) {
            return new FastExcel($data)->export('php://output', function ($person) {
                return [
                    'Nom' => $person->last_name,
                    'Prénom' => $person->first_name,
                    'Téléphone' => $person->full_phone,
                    'Email' => $person->user ? $person->user->email : '-',
                    'Date' => $person->created_at->format('d/m/Y H:i'),
                    'Statut' => $person->user ? $person->user->status : 'Sans compte',
                    'Rôles' => $person->user ? $person->user->getRoleNames()->implode(', ') : '-',
                ];
            });
        }, $filename);
    }

    public function exportPDF($exportAll = false)
    {
        if ($exportAll) {
            $data = $this->getAllMatchingData();
            $filename = 'utilisateurs_tous_' . date('Y-m-d_His');
        } else {
            $data = $this->getCurrentPageData();
            $filename = 'utilisateurs_page_' . $this->getPage() . '_' . date('Y-m-d_His');
        }

        return response()->streamDownload(function () use ($data) {
            $pdf = Pdf::loadView('exports.users-pdf', ['data' => $data]);
            echo $pdf->output();
        }, $filename . '.pdf');
    }

    public function with()
    {
        return [
            'users' => $this->getUsers(),
        ];
    }
};

?>

<div>
    <div class="row g-3 mb-4 align-items-end">
        <div class="col-md-3">
            <label class="form-label small text-secondary">Recherche</label>
            <div class="input-group">
                <span class="input-group-text border-end-0">
                    <i class="bi bi-search text-secondary"></i>
                </span>
                <input type="text" wire:model.live.debounce.300ms="search" class="form-control border-start-0 ps-0"
                    placeholder="Nom, prénom, téléphone ou email...">
            </div>
        </div>

        <div class="col-md-2">
            <label class="form-label small text-secondary">Statut</label>
            <select class="form-select" wire:model.live="statusFilter">
                <option value="">Tous les statuts</option>
                <option value="with_account">Avec compte</option>
                <option value="without_account">Sans compte</option>
                <option value="{{ Status::ACTIVE }}">{{ Status::label(Status::ACTIVE) }}</option>
                <option value="{{ Status::INACTIVE }}">{{ Status::label(Status::INACTIVE) }}</option>
                <option value="banned">Banni</option>
            </select>
        </div>

        <div class="col-md-2">
            <label class="form-label small text-secondary">Rôle</label>
            <select class="form-select" wire:model.live="roleFilter">
                <option value="">Tous les rôles</option>
                <option value="{{ Roles::ADMIN }}">Admin</option>
                <option value="{{ Roles::ROOT }}">Root</option>
                <option value="user">Utilisateur (sans rôle)</option>
            </select>
        </div>

        <div class="col-md-2">
            <label class="form-label small text-secondary">Lignes/page</label>
            <select class="form-select" wire:model.live="perPage">
                <option value="10">10 lignes</option>
                <option value="15">15 lignes</option>
                <option value="25">25 lignes</option>
                <option value="50">50 lignes</option>
                <option value="100">100 lignes</option>
            </select>
        </div>

        <div class="col-md-3">
            <label class="form-label small text-secondary">&nbsp;</label>
            <div class="d-flex gap-2">
                <button type="button" class="btn btn-outline-secondary flex-grow-1" wire:click="resetFilters">
                    <i class="bi bi-eraser"></i> Effacer
                </button>
                <div class="dropdown">
                    <button type="button" class="btn btn-outline-primary dropdown-toggle" data-bs-toggle="dropdown">
                        <i class="bi bi-download"></i> Exporter
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><strong class="dropdown-header text-secondary">PDF</strong></li>
                        <li>
                            <button type="button" class="dropdown-item" wire:click="exportPDF(false)">
                                <i class="bi bi-filetype-pdf text-danger me-2"></i>
                                Cette page ({{ $users->count() }})
                            </button>
                        </li>
                        <li>
                            <button type="button" class="dropdown-item" wire:click="exportPDF(true)">
                                <i class="bi bi-filetype-pdf text-danger me-2"></i>
                                Tous les résultats ({{ $users->total() }})
                            </button>
                        </li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li><strong class="dropdown-header text-secondary">Excel</strong></li>
                        <li>
                            <button type="button" class="dropdown-item" wire:click="exportExcel(false)">
                                <i class="bi bi-filetype-xlsx text-success me-2"></i>
                                Cette page ({{ $users->count() }})
                            </button>
                        </li>
                        <li>
                            <button type="button" class="dropdown-item" wire:click="exportExcel(true)">
                                <i class="bi bi-filetype-xlsx text-success me-2"></i>
                                Tous les résultats ({{ $users->total() }})
                            </button>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    @if ($search || $statusFilter || $roleFilter)
        <div class="mb-3 d-flex flex-wrap gap-2 align-items-center">
            <span class="text-secondary small">Filtres actifs :</span>
            @if ($search)
                <span class="badge text-dark border">
                    <i class="bi bi-search"></i> {{ $search }}
                    <button type="button" class="btn-close btn-close-sm ms-2" style="font-size: 0.6rem;"
                        wire:click="$set('search', '')"></button>
                </span>
            @endif
            @if ($statusFilter)
                <span class="badge text-dark border">
                    <i class="bi bi-funnel"></i>
                    @switch($statusFilter)
                        @case('with_account')
                            Avec compte
                        @break

                        @case('without_account')
                            Sans compte
                        @break

                        @case(Status::ACTIVE)
                            {{ Status::label(Status::ACTIVE) }}
                        @break

                        @case(Status::INACTIVE)
                            {{ Status::label(Status::INACTIVE) }}
                        @break

                        @case('banned')
                            Banni
                        @break
                    @endswitch
                    <button type="button" class="btn-close btn-close-sm ms-2" style="font-size: 0.6rem;"
                        wire:click="$set('statusFilter', '')"></button>
                </span>
            @endif
            @if ($roleFilter)
                <span class="badge text-dark border">
                    <i class="bi bi-shield"></i>
                    @switch($roleFilter)
                        @case(Roles::ADMIN)
                            Admin
                        @break

                        @case(Roles::ROOT)
                            Root
                        @break

                        @case('user')
                            Utilisateur
                        @break
                    @endswitch
                    <button type="button" class="btn-close btn-close-sm ms-2" style="font-size: 0.6rem;"
                        wire:click="$set('roleFilter', '')"></button>
                </span>
            @endif
            <button type="button" class="btn btn-sm btn-link text-danger p-0" wire:click="resetFilters">
                Tout effacer
            </button>
        </div>
    @endif

    <div class="mb-3 d-flex justify-content-between align-items-center flex-wrap gap-2">
        <div class="text-secondary small">
            {{ $users->firstItem() }} à {{ $users->lastItem() }} sur {{ $users->total() }} résultat(s)
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-hover table-bordered">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nom</th>
                    <th>Prénom</th>
                    <th>Téléphone</th>
                    <th>Email</th>
                    <th>Date</th>
                    <th>Rôle</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($users as $person)
                    <tr>
                        <td>{{ $users->firstItem() + $loop->index }}</td>
                        <td>{{ $person->last_name }}</td>
                        <td>{{ $person->first_name }}</td>
                        <td>{{ $person->full_phone }}</td>
                        <td>
                            @if ($person->user)
                                <a href="mailto:{{ $person->user->email }}" class="text-decoration-none">
                                    {{ $person->user->email }}
                                </a>
                            @else
                                <span class="text-muted fst-italic">-</span>
                            @endif
                        </td>
                        <td>{{ $person->created_at->format('d/m/Y H:i') }}</td>
                        <td>
                            @if (!$person->user)
                                <span class="badge bg-secondary">Pas de compte</span>
                            @else
                                @php
                                    $roles = $person->user->getRoleNames();
                                    $count = $roles->count();
                                @endphp
                                @if ($count === 0)
                                    <span class="text-muted fst-italic">Aucun rôle</span>
                                @else
                                    @foreach ($roles->take(2) as $roleName)
                                        <span class="badge bg-secondary me-1 mb-1">{{ $roleName }}</span>
                                    @endforeach
                                    @if ($count > 2)
                                        <span class="badge bg-secondary">+{{ $count - 2 }}</span>
                                    @endif
                                @endif
                            @endif
                        </td>
                        <td>
                            <div class="d-flex align-items-center justify-content-center gap-2">
                                @can(\App\Permissions\UserPermissions::VIEW)
                                    <a href="{{ route('admin.users.show', $person->id) }}"
                                        class="icon-circle-xs text-decoration-none text-body hover-bg-secondary-25" title="Voir">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                @endcan
                                @can(\App\Permissions\UserPermissions::UPDATE)
                                    <a href="{{ route('admin.users.edit', $person->id) }}"
                                        class="icon-circle-xs text-decoration-none text-body hover-bg-secondary-25" title="Modifier">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                @endcan
                                @can(\App\Permissions\UserPermissions::DELETE)
                                    <button type="button" class="btn btn-link text-body icon-circle-xs text-decoration-none hover-bg-secondary-25 p-0 m-0"
                                        data-bs-toggle="modal" data-bs-target="#confirmModal"
                                        data-url="{{ route('admin.users.destroy', $person->id) }}" data-method="DELETE">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                @endcan
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="text-center py-4 text-muted">
                            Aucune personne trouvée.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if ($users && $users->hasPages())
        <livewire:pagination :current-page="$users->currentPage()" :last-page="$users->lastPage()" :total="$users->total()" :first-item="$users->firstItem()" :last-item="$users->lastItem()"
            :has-pages="$users->hasPages()" :on-first-page="$users->onFirstPage()" :has-more-pages="$users->hasMorePages()" />
    @endif
</div>
