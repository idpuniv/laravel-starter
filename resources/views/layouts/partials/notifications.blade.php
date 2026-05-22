@php
    $prefix = $prefix ?? '';
@endphp

<a class="nav-link d-flex align-items-center px-2 py-2 rounded-3 position-relative" href="#" role="button"
    data-bs-toggle="dropdown">
    <i class="bi bi-bell"></i>
    <span
        class="position-absolute top-0 start-100 translate-middle badge rounded-circle bg-danger p-0 d-flex align-items-center justify-content-center notification-badge"
        style="display: {{ auth()->user()->unreadNotifications->count() > 0 ? 'flex' : 'none' }};">
        {{ auth()->user()->unreadNotifications->count() > 99 ? '99+' : auth()->user()->unreadNotifications->count() }}
    </span>
</a>
<ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 mt-2">
    <li>
        <div class="dropdown-header small fw-semibold text-secondary d-flex justify-content-between align-items-center">
            NOTIFICATIONS
            <span class="badge bg-primary rounded-pill">{{ auth()->user()->unreadNotifications->count() }}</span>
        </div>
    </li>
    @php $notifications = auth()->user()->notifications()->latest()->limit(5)->get(); @endphp
    @if($notifications->count() > 0)
        @foreach($notifications as $notification)
        <li>
            <a class="dropdown-item d-flex align-items-start gap-3 py-2" href="{{ $notification->data['url'] ?? '#' }}">
                <i class="bi {{ $notification->data['icon'] ?? 'bi-bell' }} text-primary mt-1"></i>
                <div class="flex-grow-1">
                    <div class="fw-semibold">{{ $notification->data['title'] ?? 'Notification' }}</div>
                    <small class="text-secondary">{{ $notification->data['message'] ?? '' }}</small>
                    <small class="text-muted d-block mt-1">{{ $notification->created_at->diffForHumans() }}</small>
                </div>
                @if(!$notification->read_at)
                <span class="badge bg-primary rounded-pill">Nouvelle</span>
                @endif
            </a>
        </li>
        @endforeach
        <li>
            <hr class="dropdown-divider my-1">
        </li>
        <li>
            <a class="dropdown-item text-center small" href="{{ route($prefix . 'notifications.index') }}">
                Voir toutes les notifications
            </a>
        </li>
    @else
        <li>
            <div class="dropdown-item text-center text-muted py-3">
                <i class="bi bi-bell fs-4 d-block mb-2"></i>
                <small>Aucune notification</small>
            </div>
        </li>
    @endif
    <li>
        <hr class="dropdown-divider my-1">
    </li>
    <li>
        <form action="{{ route($prefix . 'notifications.mark-all-read') }}" method="POST">
            @csrf
            <button type="submit" class="dropdown-item d-flex align-items-center gap-3 py-2 text-secondary" style="background: none; border: none; width: 100%; cursor: pointer;">
                <i class="bi bi-bell-slash"></i>
                <span>Tout marquer comme lu</span>
            </button>
        </form>
    </li>
</ul>