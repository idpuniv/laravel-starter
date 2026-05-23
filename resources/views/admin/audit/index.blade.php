{{-- resources/views/admin/audit/index.blade.php --}}
@section('title', 'Journal d\'audit')
<x-admin-layout>
    <div class="container py-4">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h3>Journal d'audit</h3>
                <div class="d-flex gap-2 mt-2">
                    <span class="badge bg-secondary">Total: {{ $stats['total'] }}</span>
                    <span class="badge bg-info">Aujourd'hui: {{ $stats['today'] }}</span>
                </div>
            </div>
            <div class="d-flex gap-2">
                <form action="{{ route('admin.audit.clear-old') }}" method="POST">
                    @csrf
                    <button class="btn btn-sm btn-outline-danger" onclick="return confirm('Supprimer les logs > 90 jours ?')">
                        Nettoyer
                    </button>
                </form>
                <a href="{{ route('admin.audit.export') }}" class="btn btn-sm btn-outline-success">
                    Exporter JSON
                </a>
            </div>
        </div>

        {{-- Filtres --}}
        <div class="card mb-4">
            <div class="card-body">
                <form method="GET" class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label small">Événement</label>
                        <select name="event" class="form-select">
                            <option value="">Tous</option>
                            <option value="create">Create</option>
                            <option value="update">Update</option>
                            <option value="delete">Delete</option>
                            <option value="login">Login</option>
                            <option value="logout">Logout</option>
                            <option value="export">Export</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label small">Statut</label>
                        <select name="outcome" class="form-select">
                            <option value="">Tous</option>
                            <option value="success">Succès</option>
                            <option value="failure">Échec</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label small">Date début</label>
                        <input type="date" name="date_from" class="form-control">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label small">Date fin</label>
                        <input type="date" name="date_to" class="form-control">
                    </div>
                    <div class="col-md-3 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary w-100">Filtrer</button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Table des logs --}}
        <div class="card">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Utilisateur</th>
                                <th>Action</th>
                                <th>Cible</th>
                                <th>IP</th>
                                <th>Statut</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($logs as $log)
                                <tr>
                                    <td>{{ $log->created_at->format('d/m/Y H:i:s') }}</td>
                                    <td>
                                        @if($log->actor)
                                            {{ $log->actor->name ?? $log->actor->email }}
                                        @else
                                            <span class="text-muted">{{ $log->system_user ?? 'Système' }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge bg-secondary">{{ $log->event }}</span>
                                    </td>
                                    <td>
                                        @if($log->target_identifier)
                                            {{ $log->target_identifier }}
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>{{ $log->ip_address ?? '-' }}</td>
                                    <td>
                                        <span class="badge bg-{{ $log->event_outcome == 'success' ? 'success' : 'danger' }}">
                                            {{ $log->event_outcome }}
                                        </span>
                                    </td>
                                    <td class="text-end">
                                        <a href="{{ route('admin.audit.show', $log) }}" class="btn btn-sm btn-link">Détails</a>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="7" class="text-center py-4">Aucun log</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        
        <div class="mt-3">{{ $logs->links() }}</div>
    </div>
</x-admin-layout>