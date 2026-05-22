{{-- resources/views/admin/job-tracking/index.blade.php --}}
@section('title', 'Suivi des jobs')
<x-admin-layout>
    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h3>Suivi des jobs</h3>
                <div class="d-flex gap-2 mt-2">
                    <span class="badge bg-secondary">Total: {{ $stats['total'] }}</span>
                    <span class="badge bg-info">Pending: {{ $stats['pending'] }}</span>
                    <span class="badge bg-warning">Processing: {{ $stats['processing'] }}</span>
                    <span class="badge bg-success">Completed: {{ $stats['completed'] }}</span>
                    <span class="badge bg-danger">Failed: {{ $stats['failed'] }}</span>
                </div>
            </div>
            <select id="statusFilter" class="form-select w-auto">
                <option value="">Tous</option>
                <option value="pending">En attente</option>
                <option value="processing">En cours</option>
                <option value="completed">Terminés</option>
                <option value="failed">Échoués</option>
            </select>
        </div>

        <div class="card">
            <div class="card-body p-0">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nom</th>
                            <th>Status</th>
                            <th>Progression</th>
                            <th>User</th>
                            <th>Créé</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($jobs as $job)
                            <tr>
                                <td>#{{ $job->id }}</td>
                                <td>{{ $job->name ?? class_basename($job->job_class) }}</td>
                                <td>
                                    @php
                                        $statusClass = match($job->status) {
                                            'pending' => 'info',
                                            'processing' => 'warning',
                                            'completed' => 'success',
                                            'failed' => 'danger',
                                            default => 'secondary'
                                        };
                                    @endphp
                                    <span class="badge bg-{{ $statusClass }}">{{ $job->status }}</span>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="progress w-100" style="height: 6px;">
                                            <div class="progress-bar" style="width: {{ $job->progress }}%"></div>
                                        </div>
                                        <small>{{ $job->progress }}%</small>
                                    </div>
                                </td>
                                <td>{{ $job->user_id ?? 'N/A' }}</td>
                                <td>{{ $job->created_at->diffForHumans() }}</td>
                                <td class="text-end">
                                    <div class="d-flex gap-2 justify-content-end">
                                        <a href="{{ route('admin.job-tracking.show', $job) }}" class="btn btn-sm btn-link">Détails</a>
                                        @if($job->status == 'failed')
                                            <form action="{{ route('admin.job-tracking.retry', $job->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button class="btn btn-sm btn-link text-warning">Relancer</button>
                                            </form>
                                        @endif
                                        <form action="{{ route('admin.job-tracking.destroy', $job) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-sm btn-link text-danger" onclick="return confirm('Supprimer ?')">Suppr</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="7" class="text-center py-4">Aucun job</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="mt-3">{{ $jobs->links() }}</div>
    </div>

    @push('scripts')
    <script>
        document.getElementById('statusFilter')?.addEventListener('change', function(e) {
            let url = new URL(window.location.href);
            if(e.target.value) url.searchParams.set('status', e.target.value);
            else url.searchParams.delete('status');
            window.location.href = url.toString();
        });
    </script>
    @endpush
</x-admin-layout>