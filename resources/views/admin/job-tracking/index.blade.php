@section('title', 'Suivi des jobs')
<x-admin-layout>
    <div class="container py-4">

        {{-- En-tête avec statistiques --}}
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
            <div>
                <h1 class="h3 fw-semibold m-0 d-flex align-items-center gap-2">
                    Suivi des jobs
                </h1>
                <p class="text-muted small mt-1 mb-0">
                    {{ $jobs->total() }} job(s) au total
                </p>
            </div>
            <div class="d-flex gap-2">
                <select id="statusFilter" class="form-select w-auto">
                    <option value="">Tous</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>En attente</option>
                    <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>En cours</option>
                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Terminés</option>
                    <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>Échoués</option>
                </select>
            </div>
        </div>

        {{-- Cartes statistiques --}}
        <div class="row g-3 mb-4">
            <div class="col">
                <div class="card border-0 shadow-sm rounded-4">
                    <div class="card-body p-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <span class="text-muted small">Total</span>
                                <div class="h5 mb-0 fw-bold">{{ $stats['total'] }}</div>
                            </div>
                            <div class="rounded-circle bg-secondary bg-opacity-10 p-2">
                                <i class="bi bi-list-check text-secondary"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card border-0 shadow-sm rounded-4">
                    <div class="card-body p-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <span class="text-muted small">En attente</span>
                                <div class="h5 mb-0 fw-bold text-info">{{ $stats['pending'] }}</div>
                            </div>
                            <div class="rounded-circle bg-info bg-opacity-10 p-2">
                                <i class="bi bi-hourglass-split text-info"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card border-0 shadow-sm rounded-4">
                    <div class="card-body p-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <span class="text-muted small">En cours</span>
                                <div class="h5 mb-0 fw-bold text-warning">{{ $stats['processing'] }}</div>
                            </div>
                            <div class="rounded-circle bg-warning bg-opacity-10 p-2">
                                <i class="bi bi-arrow-repeat text-warning"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card border-0 shadow-sm rounded-4">
                    <div class="card-body p-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <span class="text-muted small">Terminés</span>
                                <div class="h5 mb-0 fw-bold text-success">{{ $stats['completed'] }}</div>
                            </div>
                            <div class="rounded-circle bg-success bg-opacity-10 p-2">
                                <i class="bi bi-check-lg text-success"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card border-0 shadow-sm rounded-4">
                    <div class="card-body p-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <span class="text-muted small">Échoués</span>
                                <div class="h5 mb-0 fw-bold text-danger">{{ $stats['failed'] }}</div>
                            </div>
                            <div class="rounded-circle bg-danger bg-opacity-10 p-2">
                                <i class="bi bi-exclamation-triangle text-danger"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Table des jobs --}}
        <div class="card shadow-sm border-0 rounded-4">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead>
                            <tr>
                                <th class="ps-4 py-3" width="80">
                                    <span class="text-muted small fw-semibold">#ID</span>
                                </th>
                                <th class="py-3">
                                    <span class="text-muted small fw-semibold">Nom</span>
                                </th>
                                <th class="py-3">
                                    <span class="text-muted small fw-semibold">Status</span>
                                </th>
                                <th class="py-3">
                                    <span class="text-muted small fw-semibold">Progression</span>
                                </th>
                                <th class="py-3">
                                    <span class="text-muted small fw-semibold">User</span>
                                </th>
                                <th class="py-3">
                                    <span class="text-muted small fw-semibold">Créé</span>
                                </th>
                                <th class="pe-4 py-3 text-end" width="120">
                                    <span class="text-muted small fw-semibold">Actions</span>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($jobs as $job)
                                <tr>
                                    <td class="ps-4">
                                        <span class="badge bg-secondary bg-opacity-10 text-secondary px-2 py-1 rounded-pill">
                                            #{{ $job->id }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="rounded-circle bg-primary bg-opacity-10 p-2">
                                                <i class="bi bi-gear text-primary"></i>
                                            </div>
                                            <div>
                                                <div class="fw-semibold">{{ $job->name ?? class_basename($job->job_class) }}</div>
                                                <div class="text-muted small">{{ $job->job_class }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        @php
                                            $statusClass = match($job->status) {
                                                'pending' => 'info',
                                                'processing' => 'warning',
                                                'completed' => 'success',
                                                'failed' => 'danger',
                                                default => 'secondary'
                                            };
                                            $statusLabel = match($job->status) {
                                                'pending' => 'En attente',
                                                'processing' => 'En cours',
                                                'completed' => 'Terminé',
                                                'failed' => 'Échoué',
                                                default => $job->status
                                            };
                                        @endphp
                                        <span class="badge bg-{{ $statusClass }} bg-opacity-10 text-{{ $statusClass }} px-2 py-1 rounded-pill">
                                            {{ $statusLabel }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center gap-2" style="min-width: 100px;">
                                            <div class="progress w-100" style="height: 6px;">
                                                <div class="progress-bar bg-{{ $statusClass }}" style="width: {{ $job->progress }}%"></div>
                                            </div>
                                            <small class="text-muted">{{ $job->progress }}%</small>
                                        </div>
                                    </td>
                                    <td>
                                        @if($job->user_id)
                                            <span class="badge border text-body px-2 py-1 rounded-pill">
                                                <i class="bi bi-person me-1 small"></i>{{ $job->user_id }}
                                            </span>
                                        @else
                                            <span class="text-muted fst-italic small">N/A</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="text-muted small">{{ $job->created_at->diffForHumans() }}</span>
                                    </td>
                                    <td class="pe-4">
                                        <div class="d-flex gap-2 justify-content-end">
                                            @can(\App\Permissions\SystemPermissions::VIEW_JOBS)
                                                <a href="{{ route('admin.job-tracking.show', $job) }}"
                                                    class="icon-circle-xs text-decoration-none text-body hover-bg-secondary-25"
                                                    title="Voir les détails">
                                                    <i class="bi bi-eye fs-6"></i>
                                                </a>
                                            @endcan

                                            @if($job->status == 'failed')
                                                @can(\App\Permissions\SystemPermissions::RETRY_JOBS)
                                                    <form action="{{ route('admin.job-tracking.retry', $job->id) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        <button type="submit"
                                                            class="icon-circle-xs text-decoration-none text-body hover-bg-secondary-25 border-0 bg-transparent p-0"
                                                            title="Relancer le job">
                                                            <i class="bi bi-arrow-repeat fs-6 text-warning"></i>
                                                        </button>
                                                    </form>
                                                @endcan
                                            @endif

                                            @can(\App\Permissions\SystemPermissions::DELETE_JOBS)
                                                <a href="#"
                                                    class="icon-circle-xs text-decoration-none text-body hover-bg-secondary-25"
                                                    data-bs-toggle="modal" data-bs-target="#confirmModal"
                                                    data-url="{{ route('admin.job-tracking.destroy', $job) }}"
                                                    data-method="DELETE" title="Supprimer le job">
                                                    <i class="bi bi-trash fs-6"></i>
                                                    <span class="visually-hidden">Supprimer</span>
                                                </a>
                                            @endcan
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-5">
                                        <i class="bi bi-clock-history fs-1 text-muted d-block mb-2"></i>
                                        <p class="text-muted mb-0">Aucun job trouvé</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Pagination --}}
        @if ($jobs->hasPages())
            <div class="mt-4 d-flex justify-content-end">
                {{ $jobs->links() }}
            </div>
        @endif

    </div>

    @push('scripts')
    <script>
        document.getElementById('statusFilter')?.addEventListener('change', function(e) {
            let url = new URL(window.location.href);
            let value = e.target.value;
            
            if (value) {
                url.searchParams.set('status', value);
            } else {
                url.searchParams.delete('status');
            }
            
            window.location.href = url.toString();
        });
    </script>
    @endpush
</x-admin-layout>