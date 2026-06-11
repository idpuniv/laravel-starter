{{-- resources/views/admin/job-tracking/show.blade.php --}}
@section('title', 'Job #'.$jobTracking->id)
<x-admin-layout>
    <div class="container py-4">

        {{-- En-tête --}}
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
            <div>
                <h1 class="h3 fw-semibold m-0 d-flex align-items-center gap-2">
                    <i class="bi bi-clock-history"></i>
                    Job #{{ $jobTracking->id }}
                </h1>
                <p class="text-muted small mt-1 mb-0">
                    {{ $jobTracking->name ?? class_basename($jobTracking->job_class) }}
                </p>
            </div>
            <div class="d-flex gap-2">
                @if($jobTracking->status == 'failed')
                    @can(\App\Permissions\SystemPermissions::RETRY_JOBS)
                        <form action="{{ route('admin.job-tracking.retry', $jobTracking->id) }}" method="POST">
                            @csrf
                            <button class="btn btn-warning">
                                <i class="bi bi-arrow-repeat me-2"></i>Relancer
                            </button>
                        </form>
                    @endcan
                @endif
                @can(\App\Permissions\SystemPermissions::DELETE_JOBS)
                    <a href="#"
                        class="btn btn-danger"
                        data-bs-toggle="modal" data-bs-target="#confirmModal"
                        data-url="{{ route('admin.job-tracking.destroy', $jobTracking) }}"
                        data-method="DELETE">
                        <i class="bi bi-trash me-2"></i>Supprimer
                    </a>
                @endcan
                <a href="{{ route('admin.job-tracking.index') }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-left me-2"></i>Retour
                </a>
            </div>
        </div>

        {{-- Badge de statut global --}}
        <div class="alert alert-{{ match($jobTracking->status) {
            'pending' => 'info',
            'processing' => 'warning',
            'completed' => 'success',
            'failed' => 'danger',
            default => 'secondary'
        } }} border-0 rounded-4 mb-4">
            <div class="d-flex align-items-center gap-3">
                <i class="bi {{ match($jobTracking->status) {
                    'pending' => 'bi-hourglass-split',
                    'processing' => 'bi-arrow-repeat',
                    'completed' => 'bi-check-circle',
                    'failed' => 'bi-exclamation-triangle',
                    default => 'bi-question-circle'
                } }} fs-1"></i>
                <div>
                    <h4 class="mb-0">Job {{ match($jobTracking->status) {
                        'pending' => 'en attente',
                        'processing' => 'en cours',
                        'completed' => 'terminé',
                        'failed' => 'échoué',
                        default => $jobTracking->status
                    } }}</h4>
                    @if($jobTracking->progress_detail)
                        <p class="mb-0 mt-1">{{ $jobTracking->progress_detail }}</p>
                    @endif
                </div>
            </div>
        </div>

        <div class="row g-4">
            {{-- Colonne gauche --}}
            <div class="col-md-6">
                {{-- Informations générales --}}
                <div class="card shadow-sm border-0 rounded-4 mb-4">
                    <div class="card-header bg-transparent border-0 pt-4 px-4">
                        <h5 class="fw-semibold m-0">
                            <i class="bi bi-info-circle me-2"></i>Informations générales
                        </h5>
                    </div>
                    <div class="card-body p-4 pt-0">
                        <table class="table table-borderless">
                            <tr>
                                <th width="40%">Nom</th>
                                <td>{{ $jobTracking->name ?? class_basename($jobTracking->job_class) }}</td>
                            </tr>
                            <tr>
                                <th>Classe</th>
                                <td><code>{{ $jobTracking->job_class }}</code></td>
                            </tr>
                            <tr>
                                <th>Queue</th>
                                <td><span class="badge bg-secondary">{{ $jobTracking->queue }}</span></td>
                            </tr>
                            <tr>
                                <th>UUID</th>
                                <td><code class="small">{{ $jobTracking->job_uuid }}</code></td>
                            </tr>
                            <tr>
                                <th>Tentatives</th>
                                <td>{{ $jobTracking->attempts ?? 0 }}</td>
                            </tr>
                        </table>
                    </div>
                </div>

                {{-- Progression --}}
                <div class="card shadow-sm border-0 rounded-4 mb-4">
                    <div class="card-header bg-transparent border-0 pt-4 px-4">
                        <h5 class="fw-semibold m-0">
                            <i class="bi bi-graph-up me-2"></i>Progression
                        </h5>
                    </div>
                    <div class="card-body p-4 pt-0">
                        <div class="mb-3">
                            <div class="d-flex justify-content-between mb-1">
                                <small class="text-muted">Avancement</small>
                                <small class="fw-semibold">{{ $jobTracking->progress }}%</small>
                            </div>
                            <div class="progress" style="height: 10px;">
                                <div class="progress-bar bg-{{ match($jobTracking->status) {
                                    'pending' => 'info',
                                    'processing' => 'warning',
                                    'completed' => 'success',
                                    'failed' => 'danger',
                                    default => 'primary'
                                } }}" style="width: {{ $jobTracking->progress }}%"></div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-6">
                                <small class="text-muted d-block">Total items</small>
                                <strong>{{ number_format($jobTracking->total_items) }}</strong>
                            </div>
                            <div class="col-6">
                                <small class="text-muted d-block">Traités</small>
                                <strong>{{ number_format($jobTracking->processed_items) }}</strong>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Colonne droite --}}
            <div class="col-md-6">
                {{-- Dates --}}
                <div class="card shadow-sm border-0 rounded-4 mb-4">
                    <div class="card-header bg-transparent border-0 pt-4 px-4">
                        <h5 class="fw-semibold m-0">
                            <i class="bi bi-calendar3 me-2"></i>Chronologie
                        </h5>
                    </div>
                    <div class="card-body p-4 pt-0">
                        <table class="table table-borderless">
                            <tr>
                                <th width="40%">Créé</th>
                                <td>{{ $jobTracking->created_at->format('d/m/Y H:i:s') }}</td>
                            </tr>
                            <tr>
                                <th>Démarré</th>
                                <td>{{ $jobTracking->started_at ? $jobTracking->started_at->format('d/m/Y H:i:s') : '-' }}</td>
                            </tr>
                            <tr>
                                <th>Terminé</th>
                                <td>{{ $jobTracking->completed_at ? $jobTracking->completed_at->format('d/m/Y H:i:s') : '-' }}</td>
                            </tr>
                            @if($jobTracking->started_at && $jobTracking->completed_at)
                            <tr>
                                <th>Durée</th>
                                <td>{{ $jobTracking->started_at->diffInSeconds($jobTracking->completed_at) }} secondes</td>
                            </tr>
                            @endif
                        </table>
                    </div>
                </div>

                {{-- Utilisateur --}}
                <div class="card shadow-sm border-0 rounded-4 mb-4">
                    <div class="card-header bg-transparent border-0 pt-4 px-4">
                        <h5 class="fw-semibold m-0">
                            <i class="bi bi-person me-2"></i>Exécuté par
                        </h5>
                    </div>
                    <div class="card-body p-4 pt-0">
                        <table class="table table-borderless">
                            <tr>
                                <th width="40%">User ID</th>
                                <td>{{ $jobTracking->user_id ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>IP Address</th>
                                <td><code>{{ $jobTracking->ip_address ?? 'N/A' }}</code></td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        {{-- Résultat --}}
        @if($jobTracking->result)
        <div class="card shadow-sm border-0 rounded-4 mb-4">
            <div class="card-header bg-transparent border-0 pt-4 px-4">
                <h5 class="fw-semibold m-0">
                    <i class="bi bi-file-text me-2"></i>Résultat
                </h5>
            </div>
            <div class="card-body p-4 pt-0">
                <pre class="bg-body-tertiary p-3 rounded-3 overflow-auto" style="max-height: 300px;">{{ json_encode($jobTracking->result, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
            </div>
        </div>
        @endif

        {{-- Erreur --}}
        @if($jobTracking->error)
        <div class="card shadow-sm border-0 rounded-4">
            <div class="card-header bg-transparent border-0 pt-4 px-4">
                <h5 class="fw-semibold m-0 text-danger">
                    <i class="bi bi-exclamation-triangle me-2"></i>Erreur
                </h5>
            </div>
            <div class="card-body p-4 pt-0">
                <pre class="bg-danger bg-opacity-10 p-3 rounded-3 text-danger overflow-auto" style="max-height: 300px;">{{ $jobTracking->error }}</pre>
            </div>
        </div>
        @endif

    </div>
</x-admin-layout>