{{-- resources/views/admin/job-tracking/show.blade.php --}}
@section('title', 'Job #'.$jobTracking->id)
<x-admin-layout>
    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3>Job #{{ $jobTracking->id }}</h3>
            <a href="{{ route('admin.job-tracking.index') }}" class="btn btn-secondary">Retour</a>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="card mb-3">
                    <div class="card-body">
                        <p><strong>Nom:</strong> {{ $jobTracking->name ?? class_basename($jobTracking->job_class) }}</p>
                        <p><strong>Classe:</strong> {{ $jobTracking->job_class }}</p>
                        <p><strong>Queue:</strong> {{ $jobTracking->queue }}</p>
                        <p><strong>Status:</strong> <span class="badge bg-{{ $jobTracking->status }}">{{ $jobTracking->status }}</span></p>
                        <p><strong>Progression:</strong> {{ $jobTracking->progress }}%</p>
                        <p><strong>Détail:</strong> {{ $jobTracking->progress_detail ?? '-' }}</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card mb-3">
                    <div class="card-body">
                        <p><strong>Total items:</strong> {{ $jobTracking->total_items }}</p>
                        <p><strong>Traités:</strong> {{ $jobTracking->processed_items }}</p>
                        <p><strong>User ID:</strong> {{ $jobTracking->user_id ?? 'N/A' }}</p>
                        <p><strong>IP:</strong> {{ $jobTracking->ip_address ?? 'N/A' }}</p>
                        <p><strong>Créé:</strong> {{ $jobTracking->created_at->format('d/m/Y H:i:s') }}</p>
                        <p><strong>Démarré:</strong> {{ $jobTracking->started_at ? $jobTracking->started_at->format('d/m/Y H:i:s') : '-' }}</p>
                        <p><strong>Terminé:</strong> {{ $jobTracking->completed_at ? $jobTracking->completed_at->format('d/m/Y H:i:s') : '-' }}</p>
                    </div>
                </div>
            </div>
        </div>

        @if($jobTracking->result)
        <div class="card mb-3">
            <div class="card-header">Résultat</div>
            <div class="card-body">
                <pre class="bg-body-tertiary p-3">{{ json_encode($jobTracking->result, JSON_PRETTY_PRINT) }}</pre>
            </div>
        </div>
        @endif

        @if($jobTracking->error)
        <div class="card">
            <div class="card-header bg-danger text-white">Erreur</div>
            <div class="card-body">
                <pre class="bg-body-tertiary p-3 text-danger">{{ $jobTracking->error }}</pre>
            </div>
        </div>
        @endif
    </div>
</x-admin-layout>