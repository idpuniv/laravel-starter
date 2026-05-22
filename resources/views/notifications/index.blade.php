{{-- resources/views/notifications/index.blade.php --}}
@section('title', 'Mes notifications')
<x-admin-layout>
    <div class="container py-4">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h3>Mes notifications</h3>
                @if($unreadCount > 0)
                    <span class="badge bg-danger mt-1">{{ $unreadCount }} non lue(s)</span>
                @endif
            </div>
            @if($unreadCount > 0)
                <form action="{{ route('notifications.mark-all-read') }}" method="POST">
                    @csrf
                    <button class="btn btn-sm btn-outline-secondary">
                        <i class="bi bi-check2-all me-1"></i>Tout marquer comme lu
                    </button>
                </form>
            @endif
        </div>

        <div class="card shadow-sm border-0 rounded-4">
            <div class="list-group list-group-flush">
                @forelse($notifications as $notification)
                    <div class="list-group-item {{ $notification->read_at ? '' : 'bg-light' }}">
                        <div class="d-flex justify-content-between align-items-start gap-3">
                            <div class="flex-grow-1">
                                <div class="d-flex align-items-center gap-2 mb-1">
                                    <i class="bi {{ $notification->data['icon'] ?? 'bi-bell' }} text-primary"></i>
                                    <strong>{{ $notification->data['title'] ?? 'Notification' }}</strong>
                                    <small class="text-muted">{{ $notification->created_at->diffForHumans() }}</small>
                                </div>
                                <p class="mb-2">{{ $notification->data['message'] ?? '' }}</p>
                                @if($notification->data['url'] ?? false)
                                    <a href="{{ $notification->data['url'] }}" class="small">Voir détails →</a>
                                @endif
                            </div>
                            @if(!$notification->read_at)
                                <form action="{{ route('notifications.mark-read', $notification->id) }}" method="POST">
                                    @csrf
                                    <button class="btn btn-sm btn-link text-muted" title="Marquer comme lu">
                                        <i class="bi bi-check2"></i>
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="text-center py-5 text-muted">
                        <i class="bi bi-inbox fs-1 d-block mb-3"></i>
                        <p class="mb-0">Aucune notification</p>
                    </div>
                @endforelse
            </div>
        </div>

        <div class="mt-4">
            {{ $notifications->links() }}
        </div>

    </div>
</x-admin-layout>