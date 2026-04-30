<!DOCTYPE html>
<html lang="fr" data-bs-theme="auto">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <meta name="theme-color" content="#1e293b">
    <meta name="color-scheme" content="dark light">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title') - {{ config('app.name') }}</title>
    <script src="{{ asset('js/color-modes.js') }}"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/css/admin.css', 'resources/js/admin.js'])
</head>

<body>
    <a href="#main-content" class="skip-to-content">Aller au contenu principal</a>

    <div id="sidebarOverlay" class="sidebar-overlay" aria-hidden="true"></div>

    <div class="app-container">
        <aside id="sidebar" class="sidebar-col" aria-label="Menu principal">
            <div class="sidebar-content">
                <div class="sidebar-profile text-center">
                    <div class="avatar d-inline-flex mx-auto" aria-label="Avatar">TA</div>
                    <div class="profile-name text-muted">Thomas Anderson</div>
                </div>

                <nav class="mt-2" aria-label="Navigation principale">
                    <ul class="nav sidebar-menu flex-column" role="menubar" data-accordion="false">
                        @foreach ($sidebarMenus as $menu)
                            @php
                                // Filtrer les enfants valides (avec route existante)
                                $validChildren = array_filter($menu['children'] ?? [], function ($child) {
                                    return isset($child['route']) && Route::has($child['route']);
                                });

                                $hasChildren = !empty($validChildren);
                                $hasRoute = isset($menu['route']) && Route::has($menu['route']);

                                // Vérifier si le parent a des enfants valides ou une route
                                if (!$hasRoute && !$hasChildren) {
                                    continue;
                                }

                                // Déterminer si le parent est actif
                                $isParentActive = $hasRoute && request()->routeIs($menu['route'] . '*');

                                // Vérifier si un enfant est actif
                                $hasActiveChild = false;
                                if ($hasChildren) {
                                    foreach ($validChildren as $child) {
                                        if (isset($child['route']) && request()->routeIs($child['route'] . '*')) {
                                            $hasActiveChild = true;
                                            break;
                                        }
                                    }
                                }

                                // Le menu parent est considéré actif si parent actif OU enfant actif
                                $isActive = $isParentActive || $hasActiveChild;
                            @endphp

                            @if (!$hasChildren)
                                {{-- Menu sans enfants (lien simple) --}}
                                <li class="nav-item" role="none">
                                    <a href="{{ route($menu['route']) }}"
                                        class="nav-link {{ $isActive ? 'active' : '' }}" role="menuitem">
                                        <i class="nav-icon {{ $menu['icon'] ?? 'bi bi-circle' }}"
                                            aria-hidden="true"></i>
                                        <p>{{ $menu['label'] }}</p>
                                    </a>
                                </li>
                            @else
                                {{-- Menu avec enfants (dropdown) --}}
                                <li class="nav-item" role="none">
                                    <button class="nav-link {{ $isActive ? 'active' : '' }}" data-treeview-toggle
                                        role="menuitem" aria-expanded="{{ $hasActiveChild ? 'true' : 'false' }}"
                                        aria-haspopup="true">
                                        <i class="nav-icon {{ $menu['icon'] ?? 'bi bi-folder' }}"
                                            aria-hidden="true"></i>
                                        <p>
                                            {{ $menu['label'] }}
                                            @if (isset($menu['badge']))
                                                <span class="nav-badge"
                                                    aria-label="{{ $menu['badge']['label'] ?? 'notification' }}">
                                                    {{ $menu['badge']['value'] ?? $menu['badge'] }}
                                                </span>
                                            @endif
                                            <i class="nav-arrow bi bi-chevron-right" aria-hidden="true"></i>
                                        </p>
                                    </button>
                                    <ul class="nav nav-treeview {{ $hasActiveChild ? 'show' : '' }}" role="menu">
                                        @foreach ($validChildren as $child)
                                            <li class="nav-item" role="none">
                                                <a href="{{ route($child['route']) }}"
                                                    class="nav-link {{ request()->routeIs($child['route'] . '*') ? 'active' : '' }}"
                                                    role="menuitem">
                                                    <i class="nav-icon bi bi-circle" aria-hidden="true"></i>
                                                    <p>{{ $child['label'] }}</p>
                                                </a>
                                            </li>
                                        @endforeach
                                    </ul>
                                </li>
                            @endif
                        @endforeach

                        {{-- Séparateur de déconnexion --}}
                        <li class="nav-item mt-2" role="none">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="nav-link text-danger-sidebar" role="menuitem">
                                    <i class="nav-icon bi bi-box-arrow-right" aria-hidden="true"></i>
                                    <p>Déconnexion</p>
                                </button>
                            </form>
                        </li>
                    </ul>
                </nav>
            </div>

            <div class="sidebar-footer">
                <i class="bi bi-shield-check" aria-hidden="true"></i> v2.0.1
                <span class="mx-1" aria-hidden="true">•</span>
                <i class="bi bi-database" aria-hidden="true"></i> Secure
            </div>
        </aside>

        <div class="main-col">
            <nav class="navbar-fixed navbar navbar-expand-lg" aria-label="Barre de navigation supérieure">
                <div class="container-fluid py-2">
                    <div class="d-flex align-items-center">
                        <button class="sidebar-toggle-btn" id="sidebarToggleBtn"
                            aria-label="Afficher ou masquer le menu" type="button">
                            <i class="bi bi-list fs-4" aria-hidden="true"></i>
                        </button>
                        <span class="navbar-brand fw-semibold ms-2">
                            <i class="bi bi-activity text-primary" aria-hidden="true"></i>
                            Admin<span class="text-primary">Dashboard</span>
                        </span>
                    </div>

                    <div class="ms-auto d-flex align-items-center gap-3">
                        <div class="dropdown">
                            <button class="btn btn-link text-decoration-none p-0" data-bs-toggle="dropdown"
                                aria-expanded="false" aria-label="Notifications" type="button">
                                <i class="bi bi-bell fs-5" aria-hidden="true"></i>
                                <span class="badge bg-danger rounded-pill ms-1"
                                    aria-label="3 notifications non lues">3</span>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                                <li><a class="dropdown-item" href="notifications.html">🔔 3 nouvelles alertes</a></li>
                                <li><a class="dropdown-item" href="messages.html">📧 4 messages</a></li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li><a class="dropdown-item text-center" href="notifications-tout.html">Voir tout</a>
                                </li>
                            </ul>
                        </div>

                        <div class="dropdown">
                            <button class="d-flex align-items-center text-decoration-none bg-transparent border-0"
                                data-bs-toggle="dropdown" aria-expanded="false" aria-label="Menu utilisateur"
                                type="button">
                                <div class="avatar me-2" style="width: 32px; height: 32px; font-size: 14px;"
                                    aria-hidden="true">TA</div>
                                <span class="d-none d-sm-inline">Thomas</span>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                                <li><a class="dropdown-item" href="profil.html"><i class="bi bi-person"
                                            aria-hidden="true"></i> Profil</a></li>
                                <li><a class="dropdown-item" href="params.html"><i class="bi bi-gear"
                                            aria-hidden="true"></i> Paramètres</a></li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li>
                                    <form method="POST" action="/logout">
                                        @csrf
                                        <a class="dropdown-item text-danger" role="menuitem" href="/logout"
                                            onclick="event.preventDefault();
                                        this.closest('form').submit();">
                                            <i class="nav-icon bi bi-box-arrow-right" aria-hidden="true"></i>
                                            Déconnexion
                                        </a>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </nav>

            <main id="main-content" class="content-scrollable" tabindex="-1">
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
                    @yield('content-header')
                    <div class="status">
                        @if (session('success'))
                            <div class="alert alert-success">{{ session('success') }}</div>
                        @endif

                        @if (session('error'))
                            <div class="alert alert-danger">{{ session('error') }}</div>
                        @endif
                    </div>
                    {{-- <button class="btn btn-primary btn-sm" type="button">
                        <i class="bi bi-plus-circle" aria-hidden="true"></i> Nouveau
                    </button> --}}
                </div>
                {{ $slot }}
            </main>

            <footer class="footer-fixed">
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <small class="text-secondary">&copy; 2026 AdminDashboard. Tous droits réservés.</small>
                    <div>
                        <a href="confidentialite.html"
                            class="text-secondary text-decoration-none me-3 small">Confidentialité</a>
                        <a href="mentions-legales.html" class="text-secondary text-decoration-none small">Mentions
                            légales</a>
                    </div>
                </div>
            </footer>
        </div>
    </div>

</body>

</html>
