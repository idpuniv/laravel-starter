<x-app-layout class="d-flex flex-column min-vh-100">

    @vite(['resources/css/guest.css'])
    <x-theme-switch />

    <header class="d-flex fixed-top flex-column">
        <nav class="navbar navbar-expand-lg">
            <div class="container-fluid">
                <!-- Logo -->
                <a class="navbar-brand" href="#">
                    <i class="bi bi-rocket me-2 text-accent"></i><span class="text-accent fw-bold">App</span>
                </a>

                <!-- Éléments à droite (toujours visibles) -->
                <div class="d-flex align-items-center gap-1 order-lg-1 ms-auto pe-2">
                    <!-- Langue -->
                    <div class="nav-item dropdown">
                        <a class="nav-link d-flex align-items-center gap-1 px-2 py-2 rounded-3" href="#"
                            role="button" data-bs-toggle="dropdown">
                            <i class="bi bi-globe"></i>
                            <span class="d-none d-md-inline small">FR</span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 mt-2">
                            <li>
                                <h6 class="dropdown-header small fw-semibold text-secondary">LANGUES</h6>
                            </li>
                            <li><a class="dropdown-item d-flex align-items-center gap-2 py-2" href="#">
                                    <i class="bi bi-flag"></i> Français (FR)
                                </a></li>
                            <li><a class="dropdown-item d-flex align-items-center gap-2 py-2" href="#">
                                    <i class="bi bi-flag"></i> English (EN)
                                </a></li>
                        </ul>
                    </div>

                    <!-- Notifications -->
                    <div class="nav-item dropdown">
                        <a class="nav-link d-flex align-items-center px-2 py-2 rounded-3 position-relative"
                            href="#" role="button" data-bs-toggle="dropdown">
                            <i class="bi bi-bell"></i>
                            <span
                                class="position-absolute top-0 start-100 translate-middle badge rounded-circle bg-danger p-0 d-flex align-items-center justify-content-center notification-badge">3</span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 mt-2">
                            <li>
                                <div
                                    class="dropdown-header small fw-semibold text-secondary d-flex justify-content-between align-items-center">
                                    NOTIFICATIONS
                                    <span class="badge bg-primary rounded-pill">3</span>
                                </div>
                            </li>
                            <li><a class="dropdown-item d-flex align-items-start gap-3 py-2" href="#">
                                    <i class="bi bi-envelope text-primary mt-1"></i>
                                    <div class="flex-grow-1">
                                        <div class="fw-semibold">Nouveau message</div>
                                        <small class="text-secondary">Il y a 5 minutes</small>
                                    </div>
                                    <span class="badge bg-primary rounded-pill">2</span>
                                </a></li>
                            <li><a class="dropdown-item d-flex align-items-start gap-3 py-2" href="#">
                                    <i class="bi bi-arrow-up-circle text-success mt-1"></i>
                                    <div class="flex-grow-1">
                                        <div class="fw-semibold">Mise à jour disponible</div>
                                        <small class="text-secondary">Version 2.5.0</small>
                                    </div>
                                </a></li>
                            <li>
                                <hr class="dropdown-divider my-1">
                            </li>
                            <li><a class="dropdown-item d-flex align-items-center gap-3 py-2 text-secondary"
                                    href="#">
                                    <i class="bi bi-bell-slash"></i>
                                    <span>Tout marquer comme lu</span>
                                </a></li>
                        </ul>
                    </div>

                    <!-- Profil -->
                    <div class="nav-item">
                        @include('layouts.partials.profile')
                    </div>
                </div>

                <!-- Bouton toggler pour mobile -->
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarMainContent">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <!-- Menu principal -->
                <div class="collapse navbar-collapse" id="navbarMainContent">
                    <ul class="navbar-nav me-auto">
                        <li class="nav-item">
                            <a class="nav-link active" href="/">Accueil</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/components">Composants</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="admin/dashboard">Administration</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/login">Se connecter</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/register">Créer un compte</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </header>

    <!-- Main content avec padding-top pour compenser la navbar fixed -->
    <main class="flex-grow-1 d-flex flex-column" style="padding-top: 76px;">
        <div class="container-fluid d-flex flex-column flex-grow-1">
            {{ $slot }}
        </div>
    </main>

    <!-- Footer avec mt-auto pour le sticky -->
    <footer class="footer mt-auto py-4">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12 text-center">
                    <p class="mb-0">&copy; {{ date('Y') }} Mon App. Tous droits réservés.</p>
                </div>
            </div>
        </div>
    </footer>

</x-app-layout>
