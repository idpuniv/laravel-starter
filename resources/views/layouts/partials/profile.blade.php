<div class="dropdown">
    <a class="nav-link d-flex align-items-center justify-content-center px-2 py-2 rounded-3" href="#" role="button"
        data-bs-toggle="dropdown">
        <i class="bi bi-person-circle fs-5"></i>
    </a>
    <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 mt-2" style="min-width: 260px; width: auto;">
        <li>
            <div class="dropdown-header d-flex flex-column align-items-center gap-2 py-3">
                <i class="bi bi-person-circle text-secondary" style="font-size: 4rem;"></i>
                <div class="text-center">
                    <div class="fw-semibold">Jean Dupont</div>
                    <small class="text-secondary">jean.dupont@example.com</small>
                </div>
            </div>
        </li>
        <li>
            <hr class="dropdown-divider my-1">
        </li>
        <li><a class="dropdown-item d-flex align-items-center gap-3 py-2" href="/profile">
                <i class="bi bi-person"></i> Profil
                <span class="badge bg-secondary rounded-pill ms-auto">80%</span>
            </a></li>
        <li><a class="dropdown-item d-flex align-items-center gap-3 py-2" href="/profile">
                <i class="bi bi-gear"></i> Paramètres
            </a></li>
        <li><a class="dropdown-item d-flex align-items-center gap-3 py-2" href="/profile#security">
                <i class="bi bi-shield-lock"></i> Sécurité
            </a></li>
        <li>
            <hr class="dropdown-divider my-1">
        </li>
        <li>
            <form method="POST" action="/logout" class="d-inline">
                @csrf
                <button type="submit" class="dropdown-item d-flex align-items-center gap-3 py-2 text-danger"
                    style="background: none; border: none; width: 100%; cursor: pointer;">
                    <i class="bi bi-box-arrow-right"></i> Déconnexion
                </button>
            </form>
        </li>
    </ul>
</div>
