<x-app-layout class="layout-fixed sidebar-expand-lg bg-body-tertiary">
    <div class="app-wrapper">
        @push('styles')
            @vite(['resources/css/admin.css', 'resources/js/admin.js'])
            <style>
                .skip-links {
                    display: none !important;
                }
            </style>
        @endpush

        <nav class="app-header navbar navbar-expand bg-body">
            <!--begin::Container-->
            <div class="container-fluid">
                <!--begin::Start Navbar Links-->
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" data-lte-toggle="sidebar" href="#" role="button">
                            <i class="bi bi-list"></i>
                        </a>
                    </li>
                    <li class="nav-item d-none d-md-block">
                        <a href="/" class="nav-link">Accueil</a>
                    </li>
                </ul>
                <!--end::Start Navbar Links-->

                <!--begin::End Navbar Links-->
                <ul class="navbar-nav ms-auto">

                    <!--begin::Notifications Dropdown Menu-->
                    <li class="nav-item dropdown">
                        <a class="nav-link" data-bs-toggle="dropdown" href="#">
                            <i class="bi bi-bell-fill fs-5"></i>
                            <span class="navbar-badge badge text-bg-danger">3</span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end">
                            <span class="dropdown-item dropdown-header">15 Notifications</span>
                            <div class="dropdown-divider"></div>
                            <a href="#" class="dropdown-item">
                                <i class="bi bi-envelope me-2"></i> 4 new messages
                                <span class="float-end text-secondary fs-7">3 mins</span>
                            </a>
                            <div class="dropdown-divider"></div>
                            <a href="#" class="dropdown-item">
                                <i class="bi bi-people-fill me-2"></i> 8 friend requests
                                <span class="float-end text-secondary fs-7">12 hours</span>
                            </a>
                            <div class="dropdown-divider"></div>
                            <a href="#" class="dropdown-item">
                                <i class="bi bi-file-earmark-fill me-2"></i> 3 new reports
                                <span class="float-end text-secondary fs-7">2 days</span>
                            </a>
                            <div class="dropdown-divider"></div>
                            <a href="#" class="dropdown-item dropdown-footer"> See All Notifications </a>
                        </div>
                    </li>
                    <!--end::Notifications Dropdown Menu-->

                    <!--begin::User Menu Dropdown-->
                    <li class="nav-item dropdown user-menu">
                        <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
                            <i class="bi bi-person-circle fs-4 user-image"></i>
                        </a>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end shadow border-0">
                            <!-- En-tête -->
                            <li class="text-center p-3 border-bottom bg-light">
                                <i class="bi bi-person-circle fs-1 text-secondary mb-2"></i>
                                <div class="fw-bold">Admin</div>
                                <small class="text-secondary">admin@email.com</small>
                            </li>

                            <!-- Menu actions -->
                            <li><a class="dropdown-item" href="/profile"><i class="bi bi-person me-2"></i>Profil</a>
                            </li>
                            <li><a class="dropdown-item" href="/profile#security"><i
                                        class="bi bi-shield-lock me-2"></i>Sécurité</a></li>
                            <li><a class="dropdown-item" href="#"><i class="bi bi-bell me-2"></i>Notifications</a>
                            </li>

                            <li>
                                <hr class="dropdown-divider">
                            </li>

                            <form method="POST" action="/logout">
                                @csrf
                                <a class="dropdown-item d-flex align-items-center gap-3 py-2 text-danger"
                                    href="/logout"
                                    onclick="event.preventDefault();
                                        this.closest('form').submit();">
                                    <i class="bi bi-box-arrow-right"></i> Déconnexion
                                </a>
                            </form>
                        </ul>
                    </li>
                    <!--end::User Menu Dropdown-->
                </ul>
                <!--end::End Navbar Links-->
            </div>
            <!--end::Container-->
        </nav>

        <aside class="app-sidebar bg-body-secondary shadow" data-bs-theme="dark">
            <!-- Brand -->
            <div class="sidebar-brand">
                <a href="./index.html" class="brand-link">
                    <img src="./assets/img/logo.png" alt="Logo" class="brand-image opacity-75 shadow" />
                    <span class="brand-text fw-light">Admin Dashboard</span>
                </a>
            </div>

            <!-- Navigation -->
            <div class="sidebar-wrapper">
                <nav class="mt-2">
                    @if (View::exists('layouts.partials.sidebar-menu'))
                        @include('layouts.partials.sidebar-menu')
                    @else
                        <ul class="nav sidebar-menu flex-column" data-lte-toggle="treeview">

                            <!-- ================================================== -->
                            <!-- SECTION 1 : PILOTAGE -->
                            <!-- ================================================== -->
                            <li class="nav-header">PILOTAGE</li>

                            <!-- Dashboard -->
                            <li class="nav-item">
                                <a href="./dashboard.html" class="nav-link">
                                    <i class="nav-icon bi bi-speedometer2"></i>
                                    <p>Dashboard</p>
                                </a>
                            </li>

                            <!-- Statistiques -->
                            <li class="nav-item">
                                <a href="./statistiques.html" class="nav-link">
                                    <i class="nav-icon bi bi-graph-up"></i>
                                    <p>Statistiques</p>
                                </a>
                            </li>

                            <!-- ================================================== -->
                            <!-- SECTION 2 : IDENTITÉ & ORGANISATION -->
                            <!-- ================================================== -->
                            <li class="nav-header mt-3">IDENTITÉ & ORGANISATION</li>

                            <!-- Utilisateurs -->
                            <li class="nav-item">
                                <a href="#" class="nav-link">
                                    <i class="nav-icon bi bi-people"></i>
                                    <p>
                                        Utilisateurs
                                        <i class="nav-arrow bi bi-chevron-right"></i>
                                    </p>
                                </a>
                                <ul class="nav nav-treeview">
                                    <li class="nav-item">
                                        <a href="./users/list.html" class="nav-link">
                                            <i class="nav-icon bi bi-circle"></i>
                                            <p>Liste des utilisateurs</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="./users/add.html" class="nav-link">
                                            <i class="nav-icon bi bi-person-plus"></i>
                                            <p>Ajouter un utilisateur</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="./users/profile.html" class="nav-link">
                                            <i class="nav-icon bi bi-person-badge"></i>
                                            <p>Profils utilisateurs</p>
                                        </a>
                                    </li>
                                </ul>
                            </li>

                            <!-- Groupes -->
                            <li class="nav-item">
                                <a href="#" class="nav-link">
                                    <i class="nav-icon bi bi-building"></i>
                                    <p>
                                        Groupes
                                        <i class="nav-arrow bi bi-chevron-right"></i>
                                    </p>
                                </a>
                                <ul class="nav nav-treeview">
                                    <li class="nav-item">
                                        <a href="./groups/list.html" class="nav-link">
                                            <i class="nav-icon bi bi-circle"></i>
                                            <p>Liste des groupes</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="./groups/add.html" class="nav-link">
                                            <i class="nav-icon bi bi-plus-circle"></i>
                                            <p>Créer un groupe</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="./groups/members.html" class="nav-link">
                                            <i class="nav-icon bi bi-person-arms-up"></i>
                                            <p>Membres & affectations</p>
                                        </a>
                                    </li>
                                </ul>
                            </li>

                            <!-- ================================================== -->
                            <!-- SECTION 3 : GOUVERNANCE & ACCÈS -->
                            <!-- ================================================== -->
                            <li class="nav-header mt-3">GOUVERNANCE & ACCÈS</li>

                            <!-- Rôles & Permissions -->
                            <li class="nav-item">
                                <a href="#" class="nav-link">
                                    <i class="nav-icon bi bi-shield-lock"></i>
                                    <p>
                                        Rôles & Permissions
                                        <i class="nav-arrow bi bi-chevron-right"></i>
                                    </p>
                                </a>
                                <ul class="nav nav-treeview">
                                    <li class="nav-item">
                                        <a href="./roles/list.html" class="nav-link">
                                            <i class="nav-icon bi bi-circle"></i>
                                            <p>Gestion des rôles</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="./permissions/list.html" class="nav-link">
                                            <i class="nav-icon bi bi-circle"></i>
                                            <p>Permissions</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="./roles/assign.html" class="nav-link">
                                            <i class="nav-icon bi bi-person-check"></i>
                                            <p>Attribuer des rôles</p>
                                        </a>
                                    </li>
                                </ul>
                            </li>

                            <!-- Sécurité -->
                            <li class="nav-item">
                                <a href="#" class="nav-link">
                                    <i class="nav-icon bi bi-shield-check"></i>
                                    <p>
                                        Sécurité
                                        <i class="nav-arrow bi bi-chevron-right"></i>
                                    </p>
                                </a>
                                <ul class="nav nav-treeview">
                                    <li class="nav-item">
                                        <a href="./security/mfa.html" class="nav-link">
                                            <i class="nav-icon bi bi-circle"></i>
                                            <p>Authentification MFA</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="./security/sessions.html" class="nav-link">
                                            <i class="nav-icon bi bi-circle"></i>
                                            <p>Gestion des sessions</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="./security/encryption.html" class="nav-link">
                                            <i class="nav-icon bi bi-circle"></i>
                                            <p>Chiffrement & certificats</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="./security/policies.html" class="nav-link">
                                            <i class="nav-icon bi bi-circle"></i>
                                            <p>Politiques de sécurité</p>
                                        </a>
                                    </li>
                                </ul>
                            </li>

                            <!-- ================================================== -->
                            <!-- SECTION 4 : RESSOURCES TECHNIQUES -->
                            <!-- ================================================== -->
                            <li class="nav-header mt-3">RESSOURCES TECHNIQUES</li>

                            <!-- Base de données -->
                            <li class="nav-item">
                                <a href="#" class="nav-link">
                                    <i class="nav-icon bi bi-database"></i>
                                    <p>
                                        Base de données
                                        <i class="nav-arrow bi bi-chevron-right"></i>
                                    </p>
                                </a>
                                <ul class="nav nav-treeview">
                                    <li class="nav-item">
                                        <a href="./database/backup.html" class="nav-link">
                                            <i class="nav-icon bi bi-save"></i>
                                            <p>Sauvegardes</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="./database/export.html" class="nav-link">
                                            <i class="nav-icon bi bi-file-earmark-spreadsheet"></i>
                                            <p>Export / Import</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="./database/optimize.html" class="nav-link">
                                            <i class="nav-icon bi bi-speedometer"></i>
                                            <p>Optimisation & index</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="./database/maintenance.html" class="nav-link">
                                            <i class="nav-icon bi bi-tools"></i>
                                            <p>Maintenance DB</p>
                                        </a>
                                    </li>
                                </ul>
                            </li>

                            <!-- Audit -->
                            <li class="nav-item">
                                <a href="#" class="nav-link">
                                    <i class="nav-icon bi bi-eye"></i>
                                    <p>
                                        Audit
                                        <i class="nav-arrow bi bi-chevron-right"></i>
                                    </p>
                                </a>
                                <ul class="nav nav-treeview">
                                    <li class="nav-item">
                                        <a href="./audit/logs.html" class="nav-link">
                                            <i class="nav-icon bi bi-journal-text"></i>
                                            <p>Logs système</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="./audit/traces.html" class="nav-link">
                                            <i class="nav-icon bi bi-clock-history"></i>
                                            <p>Traçabilité des actions</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="./audit/alerts.html" class="nav-link">
                                            <i class="nav-icon bi bi-exclamation-triangle"></i>
                                            <p>Alertes de conformité</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="./audit/reports.html" class="nav-link">
                                            <i class="nav-icon bi bi-file-text"></i>
                                            <p>Rapports d'audit</p>
                                        </a>
                                    </li>
                                </ul>
                            </li>

                            <!-- ================================================== -->
                            <!-- SECTION 5 : CONFIGURATION -->
                            <!-- ================================================== -->
                            <li class="nav-header mt-3">CONFIGURATION</li>

                            <!-- Paramètres -->
                            <li class="nav-item">
                                <a href="#" class="nav-link">
                                    <i class="nav-icon bi bi-gear"></i>
                                    <p>
                                        Paramètres
                                        <i class="nav-arrow bi bi-chevron-right"></i>
                                    </p>
                                </a>
                                <ul class="nav nav-treeview">
                                    <li class="nav-item">
                                        <a href="./settings/general.html" class="nav-link">
                                            <i class="nav-icon bi bi-circle"></i>
                                            <p>Généraux</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="./settings/localisation.html" class="nav-link">
                                            <i class="nav-icon bi bi-circle"></i>
                                            <p>Langue & région</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="./settings/notifications.html" class="nav-link">
                                            <i class="nav-icon bi bi-circle"></i>
                                            <p>Notifications système</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="./settings/integrations.html" class="nav-link">
                                            <i class="nav-icon bi bi-circle"></i>
                                            <p>Intégrations externes</p>
                                        </a>
                                    </li>
                                </ul>
                            </li>

                            <!-- ================================================== -->
                            <!-- SECTION 6 : COMPTE -->
                            <!-- ================================================== -->
                            <li class="nav-header mt-3">COMPTE</li>

                            <!-- Compte -->
                            <li class="nav-item">
                                <a href="#" class="nav-link">
                                    <i class="nav-icon bi bi-person-circle"></i>
                                    <p>
                                        Compte
                                        <i class="nav-arrow bi bi-chevron-right"></i>
                                    </p>
                                </a>
                                <ul class="nav nav-treeview">
                                    <li class="nav-item">
                                        <a href="./account/profile.html" class="nav-link">
                                            <i class="nav-icon bi bi-person-badge"></i>
                                            <p>Mon profil</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="./account/preferences.html" class="nav-link">
                                            <i class="nav-icon bi bi-sliders2"></i>
                                            <p>Mes préférences</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="./account/password.html" class="nav-link">
                                            <i class="nav-icon bi bi-key"></i>
                                            <p>Changer mot de passe</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="./logout.html" class="nav-link text-danger">
                                            <i class="nav-icon bi bi-box-arrow-right"></i>
                                            <p>Déconnexion</p>
                                        </a>
                                    </li>
                                </ul>
                            </li>

                        </ul>
                    @endif
                </nav>
            </div>
        </aside>

        <main class="app-main">
            <!--begin::App Content Header-->
            <div class="app-content-header">
                <!--begin::Container-->
                <div class="container-fluid">
                    <!--begin::Row-->
                    <div class="row">
                        <div class="col-sm-6">
                            <h3 class="mb-0">Dashboard</h3>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-end">
                                <li class="breadcrumb-item"><a href="#">Home</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
                            </ol>
                        </div>
                        <div class="col-1"></div>
                    </div>
                    <!--end::Row-->
                </div>
                <!--end::Container-->
            </div>
            <!--end::App Content Header-->
            <!--begin::App Content-->
            <div class="app-content">
                <!--begin::Container-->
                <div class="container-fluid">
                    {{ $slot }}
                </div>
                <!--end::Container-->
            </div>
            <!--end::App Content-->
        </main>


        <footer class="app-footer">
            <!--begin::To the end-->
            <div class="float-end d-none d-sm-inline">{{ config('app.version') }}</div>
            <strong>
                Copyright &copy; 2026&nbsp;
                <a href="" class="text-decoration-none">paulido.com</a>.
            </strong>
            All rights reserved.
        </footer>
    </div>

</x-app-layout>
