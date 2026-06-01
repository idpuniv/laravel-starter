<?php

use App\Models\AuditLog;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Rap2hpoutre\FastExcel\FastExcel;
use App\Enums\Status;

new class extends Component {
    use WithPagination;

    public $search = '';
    public $eventFilter = '';
    public $outcomeFilter = '';
    public $actorFilter = '';
    public $start_date = '';
    public $end_date = '';
    public $perPage = 15;

    protected $listeners = [
        'goToPage' => 'goToPage',
        'previousPage' => 'previousPage',
        'nextPage' => 'nextPage',
        'refreshComponent' => '$refresh',
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingEventFilter()
    {
        $this->resetPage();
    }

    public function updatingOutcomeFilter()
    {
        $this->resetPage();
    }

    public function updatingActorFilter()
    {
        $this->resetPage();
    }

    public function updatingStartDate()
    {
        $this->resetPage();
    }

    public function updatingEndDate()
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
        
        return AuditLog::with('actor')
            ->when($this->search, function ($query) use ($op) {
                $searchTerm = '%' . $this->search . '%';
                $query->where(function ($q) use ($op, $searchTerm) {
                    $q->where('target_identifier', $op, $searchTerm)
                      ->orWhere('ip_address', $op, $searchTerm)
                      ->orWhere('event', $op, $searchTerm);
                });
            })
            ->when($this->eventFilter, fn($q) => $q->where('event', $this->eventFilter))
            ->when($this->outcomeFilter, fn($q) => $q->where('event_outcome', $this->outcomeFilter))
            ->when($this->actorFilter === 'system', fn($q) => $q->whereNotNull('system_user'))
            ->when($this->actorFilter && $this->actorFilter !== 'system', fn($q) => $q->where('actor_id', $this->actorFilter)->where('actor_type', 'App\\Models\\User'))
            ->when($this->start_date, fn($q) => $q->whereDate('created_at', '>=', $this->start_date))
            ->when($this->end_date, fn($q) => $q->whereDate('created_at', '<=', $this->end_date));
    }

    public function getLogs()
    {
        return $this->search()->latest('created_at')->paginate($this->perPage);
    }

    public function getAllMatchingData()
    {
        return $this->search()->latest('created_at')->get();
    }

    public function getStats()
    {
        return [
            'total' => AuditLog::count(),
            'today' => AuditLog::whereDate('created_at', today())->count(),
            'success' => AuditLog::where('event_outcome', Status::SUCCESS)->count(),
            'failure' => AuditLog::where('event_outcome', 'failure')->count(),
        ];
    }

    public function getActors()
    {
        return AuditLog::whereNotNull('actor_id')
            ->select('actor_id', 'actor_type')
            ->with('actor')
            ->distinct()
            ->get()
            ->map(fn($log) => $log->actor)
            ->filter()
            ->unique('id');
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
        $this->eventFilter = '';
        $this->outcomeFilter = '';
        $this->actorFilter = '';
        $this->start_date = '';
        $this->end_date = '';
        $this->perPage = 15;
        $this->resetPage();
    }

    public function exportExcel($exportAll = false)
    {
        if ($exportAll) {
            $data = $this->getAllMatchingData();
            $filename = 'audit_tous_' . date('Y-m-d_His') . '.xlsx';
        } else {
            $data = $this->getLogs();
            $filename = 'audit_page_' . $this->getPage() . '_' . date('Y-m-d_His') . '.xlsx';
        }

        if ($data instanceof \Illuminate\Pagination\LengthAwarePaginator) {
            $data = $data->getCollection();
        }

        return response()->streamDownload(function () use ($data) {
            return new FastExcel($data)->export('php://output', function ($log) {
                return [
                    __('fields.id.label') => $log->id,
                    __('fields.created_at.label') => $log->created_at->format('d/m/Y H:i:s'),
                    __('fields.user_id.label') => $log->actor ? $log->actor->name : ($log->system_user ?? __('Système')),
                    __('fields.event.label') => $log->event,
                    __('fields.status.label') => $log->event_outcome == 'success' ? __('Succès') : __('Échec'),
                    __('fields.target.label') => $log->target_identifier,
                    __('fields.ip_address.label') => $log->ip_address,
                    'URL' => $log->url,
                ];
            });
        }, $filename);
    }

    public function exportPDF($exportAll = false)
    {
        if ($exportAll) {
            $data = $this->getAllMatchingData();
            $filename = 'audit_tous_' . date('Y-m-d_His');
        } else {
            $data = $this->getLogs();
            $filename = 'audit_page_' . $this->getPage() . '_' . date('Y-m-d_His');
        }

        return response()->streamDownload(function () use ($data) {
            $pdf = Pdf::loadView('exports.audit-pdf', ['data' => $data]);
            echo $pdf->output();
        }, $filename . '.pdf');
    }

    public function with()
    {
        return [
            'logs' => $this->getLogs(),
            'stats' => $this->getStats(),
            'actors' => $this->getActors(),
        ];
    }
};

?>

<div>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 fw-semibold m-0 d-flex align-items-center gap-2">
                <i class="bi bi-journal-text"></i>
                {{ __('titles.audits.index') }}
            </h1>
            <p class="text-muted small mt-1 mb-0">
                {{ __("Total") }} : {{ $stats['total'] }} - {{ __("Aujourd'hui") }} : {{ $stats['today'] }}
            </p>
        </div>
        <div class="d-flex gap-2">
            @can(\App\Permissions\SystemPermissions::DELETE_AUDIT)
                <a href="#"
                    class="btn btn-sm btn-outline-danger"
                    data-bs-toggle="modal" 
                    data-bs-target="#confirmModal"
                    data-url="{{ route('admin.audit.clear-old') }}"
                    data-method="POST"
                    data-message="{{ __('Supprimer tous les logs de plus de 90 jours ?') }}">
                    <i class="bi bi-trash me-1"></i> {{ __('Nettoyer') }}
                </a>
            @endcan
            @can(\App\Permissions\SystemPermissions::EXPORT_AUDIT)
                <div class="dropdown">
                    <button type="button" class="btn btn-sm btn-outline-primary dropdown-toggle" data-bs-toggle="dropdown">
                        <i class="bi bi-download me-1"></i> {{ __('Exporter') }}
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><strong class="dropdown-header text-secondary">PDF</strong></li>
                        <li><button type="button" class="dropdown-item" wire:click="exportPDF(false)">{{ __('Cette page') }} ({{ $logs->count() }})</button></li>
                        <li><button type="button" class="dropdown-item" wire:click="exportPDF(true)">{{ __('Tous') }} ({{ $logs->total() }})</button></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><strong class="dropdown-header text-secondary">Excel</strong></li>
                        <li><button type="button" class="dropdown-item" wire:click="exportExcel(false)">{{ __('Cette page') }} ({{ $logs->count() }})</button></li>
                        <li><button type="button" class="dropdown-item" wire:click="exportExcel(true)">{{ __('Tous') }} ({{ $logs->total() }})</button></li>
                    </ul>
                </div>
            @endcan
        </div>
    </div>

    {{-- Cartes statistiques --}}
    <div class="row g-3 mb-4">
        <div class="col">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div><span class="text-muted small">{{ __('Total') }}</span><div class="h5 mb-0 fw-bold">{{ $stats['total'] }}</div></div>
                        <div class="rounded-circle bg-secondary bg-opacity-10 p-2"><i class="bi bi-database text-secondary"></i></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div><span class="text-muted small">{{ __("Aujourd'hui") }}</span><div class="h5 mb-0 fw-bold text-info">{{ $stats['today'] }}</div></div>
                        <div class="rounded-circle bg-info bg-opacity-10 p-2"><i class="bi bi-calendar text-info"></i></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div><span class="text-muted small">{{ __('Succès') }}</span><div class="h5 mb-0 fw-bold text-success">{{ $stats['success'] }}</div></div>
                        <div class="rounded-circle bg-success bg-opacity-10 p-2"><i class="bi bi-check-lg text-success"></i></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div><span class="text-muted small">{{ __('Échecs') }}</span><div class="h5 mb-0 fw-bold text-danger">{{ $stats['failure'] }}</div></div>
                        <div class="rounded-circle bg-danger bg-opacity-10 p-2"><i class="bi bi-exclamation-triangle text-danger"></i></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Filtres --}}
    <div class="card shadow-sm border-0 rounded-4 mb-4">
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-3">
                    <div class="input-group">
                        <span class="input-group-text border-end-0 bg-transparent"><i class="bi bi-search text-secondary"></i></span>
                        <input type="text" wire:model.live.debounce.300ms="search" class="form-control border-start-0" placeholder="{{ __('Rechercher...') }}">
                    </div>
                </div>
                <div class="col-md-2">
                    <select class="form-select" wire:model.live="eventFilter">
                        <option value="">{{ __('Tous les événements') }}</option>
                        <option value="create">{{ __('Créer') }}</option>
                        <option value="update">{{ __('Modifier') }}</option>
                        <option value="delete">{{ __('Supprimer') }}</option>
                        <option value="login">{{ __('Connexion') }}</option>
                        <option value="logout">{{ __('Déconnexion') }}</option>
                        <option value="export">{{ __('Exporter') }}</option>
                        <option value="import">{{ __('Importer') }}</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <select class="form-select" wire:model.live="outcomeFilter">
                        <option value="">{{ __('Tous les statuts') }}</option>
                        <option value="success">{{ __('Succès') }}</option>
                        <option value="failure">{{ __('Échec') }}</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <select class="form-select" wire:model.live="actorFilter">
                        <option value="">{{ __('Tous les utilisateurs') }}</option>
                        <option value="system">{{ __('Système') }}</option>
                        @foreach($actors as $actor)
                            <option value="{{ $actor->id }}">{{ $actor->name ?? $actor->email }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <select class="form-select" wire:model.live="perPage">
                        <option value="15">15 {{ __('lignes') }}</option>
                        <option value="25">25 {{ __('lignes') }}</option>
                        <option value="50">50 {{ __('lignes') }}</option>
                        <option value="100">100 {{ __('lignes') }}</option>
                    </select>
                </div>
                <div class="col-md-1">
                    <button type="button" class="btn btn-outline-secondary w-100" wire:click="resetFilters" title="{{ __('Effacer les filtres') }}">
                        <i class="bi bi-eraser"></i>
                    </button>
                </div>
            </div>
            <div class="row g-3 mt-2">
                <div class="col-md-3">
                    <input type="date" class="form-control" wire:model.live="start_date" placeholder="{{ __('Date de début') }}">
                </div>
                <div class="col-md-3">
                    <input type="date" class="form-control" wire:model.live="end_date" placeholder="{{ __('Date de fin') }}">
                </div>
            </div>
        </div>
    </div>

    {{-- Filtres actifs --}}
    @if($search || $eventFilter || $outcomeFilter || $actorFilter || $start_date || $end_date)
    <div class="mb-3 d-flex flex-wrap gap-2 align-items-center">
        <span class="text-secondary small">{{ __('Filtres actifs') }} :</span>
        @if($search)<span class="badge text-dark border">{{ $search }} <button type="button" class="btn-close btn-close-sm ms-2" style="font-size: 0.6rem;" wire:click="$set('search', '')"></button></span>@endif
        @if($eventFilter)<span class="badge text-dark border">{{ $eventFilter }} <button type="button" class="btn-close btn-close-sm ms-2" style="font-size: 0.6rem;" wire:click="$set('eventFilter', '')"></button></span>@endif
        @if($outcomeFilter)<span class="badge text-dark border">{{ $outcomeFilter == 'success' ? __('Succès') : __('Échec') }} <button type="button" class="btn-close btn-close-sm ms-2" style="font-size: 0.6rem;" wire:click="$set('outcomeFilter', '')"></button></span>@endif
        @if($actorFilter)<span class="badge text-dark border">{{ $actorFilter == 'system' ? __('Système') : __('Utilisateur').' #'.$actorFilter }} <button type="button" class="btn-close btn-close-sm ms-2" style="font-size: 0.6rem;" wire:click="$set('actorFilter', '')"></button></span>@endif
        @if($start_date)<span class="badge text-dark border">{{ __('Du') }} {{ $start_date }} <button type="button" class="btn-close btn-close-sm ms-2" style="font-size: 0.6rem;" wire:click="$set('start_date', '')"></button></span>@endif
        @if($end_date)<span class="badge text-dark border">{{ __('Au') }} {{ $end_date }} <button type="button" class="btn-close btn-close-sm ms-2" style="font-size: 0.6rem;" wire:click="$set('end_date', '')"></button></span>@endif
        <button type="button" class="btn btn-sm btn-link text-danger p-0" wire:click="resetFilters">{{ __('Tout effacer') }}</button>
    </div>
    @endif

    <div class="mb-3 d-flex justify-content-between align-items-center flex-wrap gap-2">
        <div class="text-secondary small">{{ $logs->firstItem() }} {{ __('à') }} {{ $logs->lastItem() }} {{ __('sur') }} {{ $logs->total() }} {{ __('résultat(s)') }}</div>
    </div>

    <div class="card shadow-sm border-0 rounded-4">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th class="ps-4 py-3">{{ __('Date') }}</th>
                            <th>{{ __('Utilisateur') }}</th>
                            <th>{{ __('Action') }}</th>
                            <th>{{ __('Cible') }}</th>
                            <th>{{ __('IP') }}</th>
                            <th>{{ __('Statut') }}</th>
                            <th class="pe-4 py-3 text-end">{{ __('Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($logs as $log)
                        <tr>
                            <td class="ps-4"><span class="text-muted small">{{ $log->created_at->format('d/m/Y H:i:s') }}</span></td>
                            <td>{{ $log->actor ? ($log->actor->name ?? $log->actor->email) : ($log->system_user ?? __('Système')) }}</td>
                            <td><span class="badge bg-secondary bg-opacity-10 text-secondary px-2 py-1 rounded-pill">{{ $log->event }}</span></td>
                            <td>{{ $log->target_identifier ?? '-' }}</td>
                            <td><code class="small">{{ $log->ip_address ?? '-' }}</code></td>
                            <td><span class="badge bg-{{ $log->event_outcome == 'success' ? 'success' : 'danger' }} bg-opacity-10 text-{{ $log->event_outcome == 'success' ? 'success' : 'danger' }} px-2 py-1 rounded-pill">{{ $log->event_outcome == 'success' ? __('Succès') : __('Échec') }}</span></td>
                            <td class="pe-4 text-end">
                                <div class="d-flex gap-2 justify-content-end">
                                    @can(\App\Permissions\SystemPermissions::VIEW_AUDIT)
                                        <a href="{{ route('admin.audit.show', $log) }}" class="icon-circle-xs text-decoration-none text-body hover-bg-secondary-25" title="{{ __('Détails') }}">
                                            <i class="bi bi-eye fs-6"></i>
                                        </a>
                                    @endcan
                                    @can(\App\Permissions\SystemPermissions::DELETE_AUDIT)
                                        <a href="#"
                                            class="icon-circle-xs text-decoration-none text-body hover-bg-secondary-25"
                                            data-bs-toggle="modal" 
                                            data-bs-target="#confirmModal"
                                            data-url="{{ route('admin.audit.destroy', $log) }}"
                                            data-method="DELETE"
                                            title="{{ __('Supprimer') }}">
                                            <i class="bi bi-trash fs-6"></i>
                                        </a>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="7" class="text-center py-5 text-secondary"><i class="bi bi-inbox fs-1 d-block mb-2"></i>{{ __('Aucune donnée disponible') }}</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @if($logs && $logs->hasPages())
    <livewire:pagination :current-page="$logs->currentPage()" :last-page="$logs->lastPage()" :total="$logs->total()" :first-item="$logs->firstItem()" :last-item="$logs->lastItem()" :has-pages="$logs->hasPages()" :on-first-page="$logs->onFirstPage()" :has-more-pages="$logs->hasMorePages()" />
    @endif
</div>