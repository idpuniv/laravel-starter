@php
    $prefix = $prefix ?? '';
@endphp

<div class="container py-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3>Notifications</h3>
            @if($unreadCount > 0)
                <span class="badge bg-danger mt-1">{{ $unreadCount }} non lue(s)</span>
            @endif
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route($prefix . 'notifications.index') }}" class="btn btn-sm btn-outline-primary">
                <i class="bi bi-arrow-repeat me-1"></i>Rafraîchir
            </a>
            @if($unreadCount > 0)
                <form action="{{ route($prefix . 'notifications.mark-all-read') }}" method="POST">
                    @csrf
                    <button class="btn btn-sm btn-outline-secondary">
                        <i class="bi bi-check2-all me-1"></i>Tout marquer comme lu
                    </button>
                </form>
            @endif
            @if($notifications->count() > 0)
                <form action="{{ route($prefix . 'notifications.destroy-all') }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-sm btn-outline-danger" onclick="return confirm('Supprimer toutes les notifications ?')">
                        <i class="bi bi-trash me-1"></i>Tout supprimer
                    </button>
                </form>
            @endif
        </div>
    </div>

    <div class="card shadow-sm border-0 rounded-4">
        <div class="list-group list-group-flush">
            @forelse($notifications as $notification)
                <div class="list-group-item {{ $notification->read_at ? 'text-muted' : '' }}">
                    <div class="d-flex justify-content-between align-items-start gap-3">
                        <div class="flex-grow-1">
                            <div class="d-flex align-items-center gap-2 mb-1">
                                <i class="bi {{ $notification->data['icon'] ?? 'bi-bell' }} {{ $notification->read_at ? 'text-muted' : 'text-primary' }}"></i>
                                <strong class="{{ $notification->read_at ? 'fw-normal text-muted' : 'fw-bold' }}">{{ $notification->data['title'] ?? 'Notification' }}</strong>
                                <small class="text-secondary">{{ $notification->created_at->diffForHumans() }}</small>
                            </div>
                            <p class="mb-2 {{ $notification->read_at ? 'text-muted' : '' }}">{{ $notification->data['message'] ?? '' }}</p>
                            @if($notification->data['url'] ?? false)
                                <a href="{{ $notification->data['url'] }}" class="small {{ $notification->read_at ? 'text-muted' : '' }}">Voir détails →</a>
                            @endif
                        </div>
                        <div class="d-flex gap-2">
                            @if(!$notification->read_at)
                                <form action="{{ route($prefix . 'notifications.mark-read', $notification->id) }}" method="POST">
                                    @csrf
                                    <button class="btn btn-sm btn-link text-secondary" title="Marquer comme lu">
                                        <i class="bi bi-check2"></i>
                                    </button>
                                </form>
                            @endif
                            <form action="{{ route($prefix . 'notifications.destroy', $notification->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-link text-danger" title="Supprimer" onclick="return confirm('Supprimer cette notification ?')">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center py-5 text-secondary">
                    <i class="bi bi-bell-slash fs-1 d-block mb-3"></i>
                    <p class="mb-0">Aucune notification</p>
                </div>
            @endforelse
        </div>
    </div>

    <div class="mt-4">
        {{ $notifications->links() }}
    </div>

</div>