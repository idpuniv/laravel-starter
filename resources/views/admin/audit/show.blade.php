{{-- resources/views/admin/audit/show.blade.php --}}
@section('title', 'Audit #'.$auditLog->id)
<x-admin-layout>
    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3>Audit #{{ $auditLog->id }}</h3>
            <a href="{{ route('admin.audit.index') }}" class="btn btn-secondary">Retour</a>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="card mb-3">
                    <div class="card-body">
                        <p><strong>Date:</strong> {{ $auditLog->created_at->format('d/m/Y H:i:s') }}</p>
                        <p><strong>Utilisateur:</strong> {{ $auditLog->actor->name ?? $auditLog->system_user ?? 'Système' }}</p>
                        <p><strong>Action:</strong> {{ $auditLog->event }}</p>
                        <p><strong>Statut:</strong> {{ $auditLog->event_outcome }}</p>
                        <p><strong>IP:</strong> {{ $auditLog->ip_address }}</p>
                        <p><strong>URL:</strong> {{ $auditLog->url }}</p>
                        <p><strong>Méthode:</strong> {{ $auditLog->http_method }}</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card mb-3">
                    <div class="card-body">
                        <p><strong>Cible type:</strong> {{ class_basename($auditLog->target_type) }}</p>
                        <p><strong>Cible ID:</strong> {{ $auditLog->target_id }}</p>
                        <p><strong>Identifiant:</strong> {{ $auditLog->target_identifier }}</p>
                        <p><strong>Contexte:</strong> {{ $auditLog->context_type }} #{{ $auditLog->context_id }}</p>
                        <p><strong>User Agent:</strong> {{ $auditLog->user_agent }}</p>
                        <p><strong>Referrer:</strong> {{ $auditLog->referrer }}</p>
                    </div>
                </div>
            </div>
        </div>

        @if($auditLog->old_values)
        <div class="card mb-3">
            <div class="card-header">Anciennes valeurs</div>
            <div class="card-body">
                <pre class="bg-body-tertiary p-3">{{ json_encode($auditLog->old_values, JSON_PRETTY_PRINT) }}</pre>
            </div>
        </div>
        @endif

        @if($auditLog->new_values)
        <div class="card">
            <div class="card-header">Nouvelles valeurs</div>
            <div class="card-body">
                <pre class="bg-body-tertiary p-3">{{ json_encode($auditLog->new_values, JSON_PRETTY_PRINT) }}</pre>
            </div>
        </div>
        @endif
    </div>
</x-admin-layout>