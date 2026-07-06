@php
    $url = $url ?? '/logout';
    $name = Auth::user()?->person?->fullName ?? __('Utilisateur');
    $initials = strtoupper(substr($name, 0, 2));
    $hasAvatar = Auth::user()?->person?->avatar ?? null;
@endphp

<div class="dropdown">
    <a class="nav-link d-flex align-items-center justify-content-center rounded-3 p-0"
       href="#"
       role="button"
       data-bs-toggle="dropdown"
       aria-expanded="false"
       style="width: 44px; height: 44px;">

        <div class="position-relative d-inline-flex" style="width: 40px; height: 40px;">

            <!-- Avatar avec initiales -->
            <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0 bg-primary bg-gradient w-100 h-100"
                 style="font-size: 0.9rem; font-weight: 600; color: white; user-select: none; line-height: 1; transition: transform 0.2s;"
                 onmouseenter="this.style.transform='scale(1.05)'"
                 onmouseleave="this.style.transform='scale(1)'">
                {{ $initials }}
            </div>

            <!-- Pastille flèche, adaptée au thème -->
            <span class="nav-avatar-caret position-absolute d-flex align-items-center justify-content-center rounded-circle shadow-sm"
                  style="width: 18px; height: 18px; bottom: -3px; right: -3px;">
                <i class="bi bi-chevron-down" style="font-size: 0.6rem;"></i>
            </span>

        </div>
    </a>

    <ul class="dropdown-menu dropdown-menu-end shadow-lg border-0 mt-2" style="min-width: 280px; width: auto; border-radius: 16px; padding: 0; overflow: hidden;">
        <li>
            <div class="d-flex flex-column align-items-center gap-2 py-4 px-3 bg-primary bg-gradient">

                <!-- Avatar dans le dropdown -->
                @if($hasAvatar)
                    <img src="{{ asset('storage/'.$hasAvatar) }}"
                         class="rounded-circle object-fit-cover border border-white border-3"
                         style="width: 80px; height: 80px; box-shadow: 0 8px 32px rgba(0,0,0,0.15);">
                @else
                    <div class="rounded-circle d-flex align-items-center justify-content-center bg-white bg-opacity-25"
                         style="width: 80px; height: 80px; border: 3px solid rgba(255,255,255,0.5); box-shadow: 0 8px 32px rgba(0,0,0,0.1);">
                        <i class="bi bi-person-fill text-white" style="font-size: 3.2rem; opacity: 0.9;"></i>
                    </div>
                @endif
                <div class="text-center">
                    <div class="fw-semibold text-white" style="font-size: 1.1rem;">
                        {{ $name }}
                    </div>
                    <small class="text-white-50" style="font-size: 0.8rem;">
                        {{ Auth::user()?->email }}
                    </small>
                </div>
            </div>
        </li>
        <li class="py-1">
            <a class="dropdown-item d-flex align-items-center gap-3 py-2 px-4" href="/profile">
                <i class="bi bi-person text-primary"></i>
                {{ __('Profil') }}
                <span class="badge bg-primary rounded-pill ms-auto">
                    80%
                </span>
            </a>
            <a class="dropdown-item d-flex align-items-center gap-3 py-2 px-4" href="/profile">
                <i class="bi bi-gear text-primary"></i>
                {{ __('Paramètres') }}
            </a>
            <a class="dropdown-item d-flex align-items-center gap-3 py-2 px-4" href="/profile#security">
                <i class="bi bi-shield-lock text-primary"></i>
                {{ __('Sécurité') }}
            </a>
        </li>
        <li class="px-2 pb-2">
            <hr class="dropdown-divider">
            <form method="POST" action="{{ $url }}" class="d-block w-100">
                @csrf
                <button type="submit"
                        class="dropdown-item d-flex align-items-center gap-3 py-2 px-4 text-danger w-100 border-0 bg-transparent"
                        style="cursor: pointer;">
                    <i class="bi bi-box-arrow-right"></i>
                    {{ __('Déconnexion') }}
                </button>
            </form>
        </li>
    </ul>
</div>

<style>
/* 
 * --navbar-surface = couleur de fond RÉELLE de ta navbar (pas body-bg).
 * A définir une seule fois sur le conteneur <nav> de ton layout, ex:
 * <nav class="navbar ..." style="--navbar-surface: #ffffff;">
 * Si tu ne la définis pas ici, on retombe sur body-bg par sécurité.
 */
.nav-avatar-caret {
    background-color: var(--navbar-surface, var(--bs-body-bg, #fff));
    border: 2px solid var(--navbar-surface, var(--bs-body-bg, #fff));
    transition: transform 0.25s ease, background-color 0.2s ease;
}
.nav-avatar-caret i {
    color: var(--bs-secondary-color, #6c757d);
    transition: color 0.2s ease;
}
[aria-expanded="true"] .nav-avatar-caret {
    transform: rotate(180deg);
    background-color: var(--bs-primary);
    border-color: var(--navbar-surface, var(--bs-body-bg, #fff));
}
[aria-expanded="true"] .nav-avatar-caret i {
    color: #fff;
}

/* Thème sombre Bootstrap 5.3 */
[data-bs-theme="dark"] .nav-avatar-caret {
    box-shadow: 0 1px 3px rgba(0,0,0,0.4) !important;
}
</style>