@section('title', __('titles.audits.show') . ' #' . $auditLog->id)
<x-admin-layout>
    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3>{{ __('titles.audits.show') }} #{{ $auditLog->id }}</h3>
            <a href="{{ route('admin.audit.index') }}" class="btn btn-secondary">{{ __('actions.back') }}</a>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="card mb-3">
                    <div class="card-body">
                        <p><strong>{{ __('fields.created_at.label') }} :</strong> {{ $auditLog->created_at->format('d/m/Y H:i:s') }}</p>
                        <p><strong>{{ __('fields.user_id.label') }} :</strong> {{ $auditLog->actor->name ?? $auditLog->system_user ?? __('Système') }}</p>
                        <p><strong>{{ __('fields.event.label') }} :</strong> {{ $auditLog->event }}</p>
                        <p><strong>{{ __('fields.status.label') }} :</strong> <span class="badge bg-{{ $auditLog->event_outcome == 'success' ? 'success' : 'danger' }}">{{ $auditLog->event_outcome == 'success' ? __('Succès') : __('Échec') }}</span></p>
                        <p><strong>{{ __('fields.ip_address.label') }} :</strong> {{ $auditLog->ip_address ?? '-' }}</p>
                        <p><strong>{{ __('fields.url') }} :</strong> {{ $auditLog->url ?? '-' }}</p>
                        <p><strong>{{ __('fields.method.label') }} :</strong> {{ $auditLog->http_method ?? '-' }}</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card mb-3">
                    <div class="card-body">
                        <p><strong>{{ __('fields.target_type.label') }} :</strong> {{ class_basename($auditLog->target_type) }}</p>
                        <p><strong>{{ __('fields.target_id.label') }} :</strong> {{ $auditLog->target_id ?? '-' }}</p>
                        <p><strong>{{ __('fields.target_identifier.label') }} :</strong> {{ $auditLog->target_identifier ?? '-' }}</p>
                        <p><strong>{{ __('fields.context.label') }} :</strong> {{ $auditLog->context_type }} #{{ $auditLog->context_id }}</p>
                        <p><strong>{{ __('fields.user_agent.label') }} :</strong> {{ $auditLog->user_agent ?? '-' }}</p>
                        <p><strong>{{ __('fields.referrer.label') }} :</strong> {{ $auditLog->referrer ?? '-' }}</p>
                    </div>
                </div>
            </div>
        </div>

        @if($auditLog->old_values)
        <div class="card mb-3">
            <div class="card-header">{{ __('messages.old_values') }}</div>
            <div class="card-body">
                <pre class="bg-body-tertiary p-3">{{ json_encode($auditLog->old_values, JSON_PRETTY_PRINT) }}</pre>
            </div>
        </div>
        @endif

        @if($auditLog->new_values)
        <div class="card">
            <div class="card-header">{{ __('messages.new_values') }}</div>
            <div class="card-body">
                <pre class="bg-body-tertiary p-3">{{ json_encode($auditLog->new_values, JSON_PRETTY_PRINT) }}</pre>
            </div>
        </div>
        @endif
    </div>
</x-admin-layout>