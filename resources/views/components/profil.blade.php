    <a href="#" class="nav-link" data-bs-toggle="dropdown">
        <i class="bi bi-person-circle fs-4 user-image"></i>
    </a>
    </a>
    <ul class="dropdown-menu dropdown-menu-end shadow border-0">
        <!-- En-tête -->
        <li class="text-center p-3 border-bottom">
            <i class="bi bi-person-circle fs-1 text-secondary mb-2"></i>
            <div class="fw-bold">Admin</div>
            <small class="text-secondary">admin@email.com</small>
        </li>

        <!-- Menu actions -->
        <li><a class="dropdown-item" href="/profile"><i class="bi bi-person me-2"></i>Profil</a>
        </li>
        <li><a class="dropdown-item" href="/profile#security"><i class="bi bi-shield-lock me-2"></i>Sécurité</a></li>
        <li><a class="dropdown-item" href="#"><i class="bi bi-bell me-2"></i>Notifications</a>
        </li>

        {{ $slot }}

        <li>
            <hr class="dropdown-divider">
        </li>

        <form method="POST" action="/logout">
            @csrf
            <a class="dropdown-item d-flex align-items-center gap-3 py-2 text-danger" href="/logout"
                onclick="event.preventDefault();
                                        this.closest('form').submit();">
                <i class="bi bi-box-arrow-right"></i> Déconnexion
            </a>
        </form>
    </ul>
