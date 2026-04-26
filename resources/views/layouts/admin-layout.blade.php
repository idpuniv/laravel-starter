<!DOCTYPE html>
<html lang="fr" data-bs-theme="auto">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <meta name="description" content="Dashboard d'administration professionnel">
    <meta name="theme-color" content="#1e293b">
    <meta name="color-scheme" content="dark light">
    <title>PAUL</title>
    <script src="{{ asset('js/color-modes.js') }}"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/css/admin.css', 'resources/js/admin.js', 'resources/js/datatable-manager.js'])
</head>

<body>
    <a href="#main-content" class="skip-to-content">Aller au contenu principal</a>

    <div id="sidebarOverlay" class="sidebar-overlay" aria-hidden="true"></div>

    <div class="app-container">
        <aside id="sidebar" class="sidebar-col" aria-label="Menu principal">
            <div class="sidebar-content">
                <div class="sidebar-profile">
                    <div class="avatar" aria-label="Avatar">TA</div>
                    <div class="profile-name">Thomas Anderson</div>
                    <div class="profile-role">Administrateur</div>
                </div>

                <nav class="mt-2" aria-label="Navigation principale">
                    <ul class="nav sidebar-menu flex-column" role="menubar" data-accordion="false">
                        <!-- Dashboard parent (plus actif, car l'enfant l'est) -->
                        <li class="nav-item" role="none">
                            <button class="nav-link" data-treeview-toggle role="menuitem" aria-expanded="true"
                                aria-haspopup="true">
                                <i class="nav-icon bi bi-speedometer2" aria-hidden="true"></i>
                                <p>
                                    Dashboard
                                    <i class="nav-arrow bi bi-chevron-right" aria-hidden="true"></i>
                                </p>
                            </button>
                            <ul class="nav nav-treeview show" role="menu">
                                <li class="nav-item" role="none">
                                    <a href="dashboard-principal.html" class="nav-link active" role="menuitem">
                                        <i class="nav-icon bi bi-circle" aria-hidden="true"></i>
                                        <p>Dashboard Principal</p>
                                    </a>
                                </li>
                                <li class="nav-item" role="none">
                                    <a href="analyses.html" class="nav-link" role="menuitem">
                                        <i class="nav-icon bi bi-circle" aria-hidden="true"></i>
                                        <p>Analyses</p>
                                    </a>
                                </li>
                                <li class="nav-item" role="none">
                                    <a href="rapports-rapides.html" class="nav-link" role="menuitem">
                                        <i class="nav-icon bi bi-circle" aria-hidden="true"></i>
                                        <p>Rapports Rapides</p>
                                    </a>
                                </li>
                            </ul>
                        </li>

                        <li class="nav-item" role="none">
                            <button class="nav-link" data-treeview-toggle role="menuitem" aria-expanded="false"
                                aria-haspopup="true">
                                <i class="nav-icon bi bi-people" aria-hidden="true"></i>
                                <p>
                                    Utilisateurs
                                    <span class="nav-badge" aria-label="12 utilisateurs">12</span>
                                    <i class="nav-arrow bi bi-chevron-right" aria-hidden="true"></i>
                                </p>
                            </button>
                            <ul class="nav nav-treeview" role="menu">
                                <li class="nav-item" role="none">
                                    <a href="utilisateurs-liste.html" class="nav-link" role="menuitem">
                                        <i class="nav-icon bi bi-list-ul" aria-hidden="true"></i>
                                        <p>Liste des utilisateurs</p>
                                    </a>
                                </li>
                                <li class="nav-item" role="none">
                                    <a href="utilisateurs-ajouter.html" class="nav-link" role="menuitem">
                                        <i class="nav-icon bi bi-person-plus" aria-hidden="true"></i>
                                        <p>Ajouter un utilisateur</p>
                                    </a>
                                </li>
                                <li class="nav-item" role="none">
                                    <a href="utilisateurs-profils.html" class="nav-link" role="menuitem">
                                        <i class="nav-icon bi bi-person-badge" aria-hidden="true"></i>
                                        <p>Profils</p>
                                    </a>
                                </li>
                                <li class="nav-item" role="none">
                                    <a href="utilisateurs-inactifs.html" class="nav-link" role="menuitem">
                                        <i class="nav-icon bi bi-person-x" aria-hidden="true"></i>
                                        <p>Comptes inactifs</p>
                                    </a>
                                </li>
                            </ul>
                        </li>

                        <li class="nav-item" role="none">
                            <button class="nav-link" data-treeview-toggle role="menuitem" aria-expanded="false"
                                aria-haspopup="true">
                                <i class="nav-icon bi bi-envelope" aria-hidden="true"></i>
                                <p>
                                    Messages
                                    <span class="nav-badge" aria-label="5 messages non lus">5</span>
                                    <i class="nav-arrow bi bi-chevron-right" aria-hidden="true"></i>
                                </p>
                            </button>
                            <ul class="nav nav-treeview" role="menu">
                                <li class="nav-item" role="none">
                                    <a href="messages-reception.html" class="nav-link" role="menuitem">
                                        <i class="nav-icon bi bi-inbox" aria-hidden="true"></i>
                                        <p>Réception</p>
                                    </a>
                                </li>
                                <li class="nav-item" role="none">
                                    <a href="messages-envoyes.html" class="nav-link" role="menuitem">
                                        <i class="nav-icon bi bi-send" aria-hidden="true"></i>
                                        <p>Messages envoyés</p>
                                    </a>
                                </li>
                                <li class="nav-item" role="none">
                                    <a href="messages-brouillons.html" class="nav-link" role="menuitem">
                                        <i class="nav-icon bi bi-file-text" aria-hidden="true"></i>
                                        <p>Brouillons</p>
                                    </a>
                                </li>
                                <li class="nav-item" role="none">
                                    <a href="messages-corbeille.html" class="nav-link" role="menuitem">
                                        <i class="nav-icon bi bi-trash" aria-hidden="true"></i>
                                        <p>Corbeille</p>
                                    </a>
                                </li>
                            </ul>
                        </li>

                        <li class="nav-item" role="none">
                            <a href="statistiques.html" class="nav-link" role="menuitem">
                                <i class="nav-icon bi bi-graph-up" aria-hidden="true"></i>
                                <p>Statistiques</p>
                            </a>
                        </li>

                        <li class="nav-header" role="separator">GESTION</li>

                        <li class="nav-item" role="none">
                            <button class="nav-link" data-treeview-toggle role="menuitem" aria-expanded="false"
                                aria-haspopup="true">
                                <i class="nav-icon bi bi-database" aria-hidden="true"></i>
                                <p>
                                    Base de données
                                    <i class="nav-arrow bi bi-chevron-right" aria-hidden="true"></i>
                                </p>
                            </button>
                            <ul class="nav nav-treeview" role="menu">
                                <li class="nav-item" role="none">
                                    <a href="db-sauvegardes.html" class="nav-link" role="menuitem">
                                        <i class="nav-icon bi bi-save" aria-hidden="true"></i>
                                        <p>Sauvegardes</p>
                                    </a>
                                </li>
                                <li class="nav-item" role="none">
                                    <button class="nav-link" data-treeview-toggle role="menuitem"
                                        aria-expanded="false" aria-haspopup="true">
                                        <i class="nav-icon bi bi-file-earmark-spreadsheet" aria-hidden="true"></i>
                                        <p>
                                            Import/Export
                                            <i class="nav-arrow bi bi-chevron-right" aria-hidden="true"></i>
                                        </p>
                                    </button>
                                    <ul class="nav nav-treeview" role="menu">
                                        <li class="nav-item" role="none">
                                            <a href="export-csv.html" class="nav-link" role="menuitem">
                                                <i class="nav-icon bi bi-download" aria-hidden="true"></i>
                                                <p>Exporter CSV</p>
                                            </a>
                                        </li>
                                        <li class="nav-item" role="none">
                                            <a href="import-json.html" class="nav-link" role="menuitem">
                                                <i class="nav-icon bi bi-upload" aria-hidden="true"></i>
                                                <p>Importer JSON</p>
                                            </a>
                                        </li>
                                        <li class="nav-item" role="none">
                                            <a href="export-sql.html" class="nav-link" role="menuitem">
                                                <i class="nav-icon bi bi-filetype-sql" aria-hidden="true"></i>
                                                <p>Exporter SQL</p>
                                            </a>
                                        </li>
                                    </ul>
                                </li>
                                <li class="nav-item" role="none">
                                    <a href="db-optimisation.html" class="nav-link" role="menuitem">
                                        <i class="nav-icon bi bi-speedometer" aria-hidden="true"></i>
                                        <p>Optimisation</p>
                                    </a>
                                </li>
                                <li class="nav-item" role="none">
                                    <a href="db-maintenance.html" class="nav-link" role="menuitem">
                                        <i class="nav-icon bi bi-tools" aria-hidden="true"></i>
                                        <p>Maintenance</p>
                                    </a>
                                </li>
                            </ul>
                        </li>

                        <li class="nav-item" role="none">
                            <button class="nav-link" data-treeview-toggle role="menuitem" aria-expanded="false"
                                aria-haspopup="true">
                                <i class="nav-icon bi bi-shield-check" aria-hidden="true"></i>
                                <p>
                                    Sécurité
                                    <i class="nav-arrow bi bi-chevron-right" aria-hidden="true"></i>
                                </p>
                            </button>
                            <ul class="nav nav-treeview" role="menu">
                                <li class="nav-item" role="none">
                                    <a href="securite-auth.html" class="nav-link" role="menuitem">
                                        <i class="nav-icon bi bi-shield-lock" aria-hidden="true"></i>
                                        <p>Authentification</p>
                                    </a>
                                </li>
                                <li class="nav-item" role="none">
                                    <a href="securite-sessions.html" class="nav-link" role="menuitem">
                                        <i class="nav-icon bi bi-clock-history" aria-hidden="true"></i>
                                        <p>Sessions actives</p>
                                    </a>
                                </li>
                                <li class="nav-item" role="none">
                                    <a href="securite-chiffrement.html" class="nav-link" role="menuitem">
                                        <i class="nav-icon bi bi-key" aria-hidden="true"></i>
                                        <p>Chiffrement</p>
                                    </a>
                                </li>
                                <li class="nav-item" role="none">
                                    <a href="securite-logs.html" class="nav-link" role="menuitem">
                                        <i class="nav-icon bi bi-shield-shaded" aria-hidden="true"></i>
                                        <p>Logs sécurité</p>
                                    </a>
                                </li>
                            </ul>
                        </li>

                        <li class="nav-item" role="none">
                            <a href="rapports.html" class="nav-link" role="menuitem">
                                <i class="nav-icon bi bi-file-text" aria-hidden="true"></i>
                                <p>Rapports</p>
                            </a>
                        </li>

                        <li class="nav-header" role="separator">CONFIGURATION</li>

                        <li class="nav-item" role="none">
                            <button class="nav-link" data-treeview-toggle role="menuitem" aria-expanded="false"
                                aria-haspopup="true">
                                <i class="nav-icon bi bi-gear" aria-hidden="true"></i>
                                <p>
                                    Paramètres
                                    <i class="nav-arrow bi bi-chevron-right" aria-hidden="true"></i>
                                </p>
                            </button>
                            <ul class="nav nav-treeview" role="menu">
                                <li class="nav-item" role="none">
                                    <a href="params-generaux.html" class="nav-link" role="menuitem">
                                        <i class="nav-icon bi bi-globe" aria-hidden="true"></i>
                                        <p>Généraux</p>
                                    </a>
                                </li>
                                <li class="nav-item" role="none">
                                    <button class="nav-link" data-treeview-toggle role="menuitem"
                                        aria-expanded="false" aria-haspopup="true">
                                        <i class="nav-icon bi bi-translate" aria-hidden="true"></i>
                                        <p>
                                            Langue
                                            <i class="nav-arrow bi bi-chevron-right" aria-hidden="true"></i>
                                        </p>
                                    </button>
                                    <ul class="nav nav-treeview" role="menu">
                                        <li class="nav-item" role="none">
                                            <a href="langue-fr.html" class="nav-link" role="menuitem">
                                                <i class="nav-icon bi bi-flag-fr" aria-hidden="true"></i>
                                                <p>Français</p>
                                            </a>
                                        </li>
                                        <li class="nav-item" role="none">
                                            <a href="langue-en.html" class="nav-link" role="menuitem">
                                                <i class="nav-icon bi bi-flag-us" aria-hidden="true"></i>
                                                <p>English</p>
                                            </a>
                                        </li>
                                        <li class="nav-item" role="none">
                                            <a href="langue-es.html" class="nav-link" role="menuitem">
                                                <i class="nav-icon bi bi-flag-es" aria-hidden="true"></i>
                                                <p>Español</p>
                                            </a>
                                        </li>
                                    </ul>
                                </li>
                                <li class="nav-item" role="none">
                                    <a href="params-notifications.html" class="nav-link" role="menuitem">
                                        <i class="nav-icon bi bi-bell" aria-hidden="true"></i>
                                        <p>Notifications</p>
                                    </a>
                                </li>
                                <li class="nav-item" role="none">
                                    <a href="params-integrations.html" class="nav-link" role="menuitem">
                                        <i class="nav-icon bi bi-plug" aria-hidden="true"></i>
                                        <p>Intégrations</p>
                                    </a>
                                </li>
                            </ul>
                        </li>

                        <li class="nav-item" role="none">
                            <a href="apparence.html" class="nav-link" role="menuitem">
                                <i class="nav-icon bi bi-palette" aria-hidden="true"></i>
                                <p>Apparence</p>
                            </a>
                        </li>

                        <li class="nav-header" role="separator">SUPPORT</li>

                        <li class="nav-item" role="none">
                            <a href="aide.html" class="nav-link" role="menuitem">
                                <i class="nav-icon bi bi-question-circle" aria-hidden="true"></i>
                                <p>Aide</p>
                            </a>
                        </li>

                        <li class="nav-item" role="none">
                            <a href="chat.html" class="nav-link" role="menuitem">
                                <i class="nav-icon bi bi-chat-dots" aria-hidden="true"></i>
                                <p>Chat & Support</p>
                            </a>
                        </li>

                        <li class="nav-header" role="separator">AUTH</li>

                        <li class="nav-item" role="none">
                            <button class="nav-link" data-treeview-toggle role="menuitem" aria-expanded="false"
                                aria-haspopup="true">
                                <i class="nav-icon bi bi-box-arrow-in-right" aria-hidden="true"></i>
                                <p>
                                    Authentification
                                    <i class="nav-arrow bi bi-chevron-right" aria-hidden="true"></i>
                                </p>
                            </button>
                            <ul class="nav nav-treeview" role="menu">
                                <li class="nav-item" role="none">
                                    <button class="nav-link" data-treeview-toggle role="menuitem"
                                        aria-expanded="false" aria-haspopup="true">
                                        <i class="nav-icon bi bi-person-circle" aria-hidden="true"></i>
                                        <p>
                                            Version 1
                                            <i class="nav-arrow bi bi-chevron-right" aria-hidden="true"></i>
                                        </p>
                                    </button>
                                    <ul class="nav nav-treeview" role="menu">
                                        <li class="nav-item" role="none">
                                            <a href="login.html" class="nav-link" role="menuitem">
                                                <i class="nav-icon bi bi-box-arrow-in-right" aria-hidden="true"></i>
                                                <p>Connexion</p>
                                            </a>
                                        </li>
                                        <li class="nav-item" role="none">
                                            <a href="register.html" class="nav-link" role="menuitem">
                                                <i class="nav-icon bi bi-person-plus" aria-hidden="true"></i>
                                                <p>Inscription</p>
                                            </a>
                                        </li>
                                    </ul>
                                </li>
                                <li class="nav-item" role="none">
                                    <a href="mot-de-passe-oublie.html" class="nav-link" role="menuitem">
                                        <i class="nav-icon bi bi-person-check" aria-hidden="true"></i>
                                        <p>Mot de passe oublié</p>
                                    </a>
                                </li>
                            </ul>
                        </li>

                        <li class="nav-item mt-2" role="none">
                            <form method="POST" action="/logout">
                                @csrf
                                <a class="nav-link text-danger-sidebar" role="menuitem" href="/logout"
                                    onclick="event.preventDefault();
                                        this.closest('form').submit();">
                                    <i class="nav-icon bi bi-box-arrow-right" aria-hidden="true"></i> Déconnexion
                                </a>
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
                    <div>
                        <h1 class="h3 fw-semibold mb-1">Dashboard</h1>
                        <p class="text-secondary mb-0">Vue d'ensemble de votre activité</p>
                    </div>
                    <button class="btn btn-primary btn-sm" type="button">
                        <i class="bi bi-plus-circle" aria-hidden="true"></i> Nouveau
                    </button>
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
