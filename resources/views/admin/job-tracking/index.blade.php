{{-- resources/views/admin/job-tracking/index.blade.php --}}
@section('title', __('titles.jobs.index'))
<x-admin-layout>
    <div class="container py-4">

        {{-- En-tête avec statistiques --}}
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
            <div>
                <h1 class="h3 fw-semibold m-0 d-flex align-items-center gap-2">
                    <i class="bi bi-clock-history"></i>
                    {{ __('titles.jobs.index') }}
                </h1>
                <p class="text-muted small mt-1 mb-0">
                    {{ __('pagination.total', ['count' => $jobs->total()]) }}
                </p>
            </div>
            <div class="d-flex gap-2">
                <select id="statusFilter" class="form-select w-auto">
                    <option value="">{{ __('Tous') }}</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>{{ __('En attente') }}</option>
                    <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>{{ __('En cours') }}</option>
                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>{{ __('Terminés') }}</option>
                    <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>{{ __('Échoués') }}</option>
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
                                <span class="text-muted small">{{ __('Total') }}</span>
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
                                <span class="text-muted small">{{ __('En attente') }}</span>
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
                                <span class="text-muted small">{{ __('En cours') }}</span>
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
                                <span class="text-muted small">{{ __('Terminés') }}</span>
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
                                <span class="text-muted small">{{ __('Échoués') }}</span>
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
                                    <span class="text-muted small fw-semibold">{{ __('fields.name.label') }}</span>
                                </th>
                                <th class="py-3">
                                    <span class="text-muted small fw-semibold">{{ __('fields.status.label') }}</span>
                                </th>
                                <th class="py-3">
                                    <span class="text-muted small fw-semibold">{{ __('fields.progress.label') }}</span>
                                </th>
                                <th class="py-3">
                                    <span class="text-muted small fw-semibold">{{ __('fields.user_id.label') }}</span>
                                </th>
                                <th class="py-3">
                                    <span class="text-muted small fw-semibold">{{ __('fields.created_at.label') }}</span>
                                </th>
                                <th class="pe-4 py-3 text-end" width="120">
                                    <span class="text-muted small fw-semibold">{{ __('Actions') }}</span>
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
                                                'pending' => __('En attente'),
                                                'processing' => __('En cours'),
                                                'completed' => __('Terminé'),
                                                'failed' => __('Échoué'),
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
                                            <span class="text-muted fst-italic small">{{ __('N/A') }}</span>
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
                                                    title="{{ __('Voir les détails') }}">
                                                    <i class="bi bi-eye fs-6"></i>
                                                </a>
                                            @endcan

                                            @if($job->status == 'failed')
                                                @can(\App\Permissions\SystemPermissions::RETRY_JOBS)
                                                    <form action="{{ route('admin.job-tracking.retry', $job->id) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        <button type="submit"
                                                            class="icon-circle-xs text-decoration-none text-body hover-bg-secondary-25 border-0 bg-transparent p-0"
                                                            title="{{ __('Relancer le job') }}">
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
                                                    data-method="DELETE" title="{{ __('Supprimer le job') }}">
                                                    <i class="bi bi-trash fs-6"></i>
                                                    <span class="visually-hidden">{{ __('Supprimer') }}</span>
                                                </a>
                                            @endcan
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-5">
                                        <i class="bi bi-clock-history fs-1 text-muted d-block mb-2"></i>
                                        <p class="text-muted mb-0">{{ __('messages.no_data') }}</p>
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