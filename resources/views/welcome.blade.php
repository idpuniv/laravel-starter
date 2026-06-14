<x-guest-layout>
    <style>
        .balloon-1 {
            top: 5%;
            left: -2%;
            font-size: 280px;
            color: var(--bs-primary);
            opacity: 0.06;
        }

        .balloon-2 {
            top: 20%;
            right: -5%;
            font-size: 320px;
            color: var(--bs-secondary);
            opacity: 0.04;
        }

        .balloon-3 {
            bottom: 10%;
            left: -3%;
            font-size: 260px;
            color: var(--bs-success);
            opacity: 0.05;
        }

        .balloon-4 {
            bottom: 30%;
            right: -4%;
            font-size: 240px;
            color: var(--bs-info);
            opacity: 0.04;
        }

        .balloon-5 {
            top: 45%;
            left: 15%;
            font-size: 200px;
            color: var(--bs-warning);
            opacity: 0.06;
        }

        .balloon-6 {
            top: 70%;
            right: 25%;
            font-size: 190px;
            color: var(--bs-danger);
            opacity: 0.04;
        }

        @keyframes float {

            0%,
            100% {
                transform: translateY(0px) rotate(0deg);
            }

            50% {
                transform: translateY(-15px) rotate(2deg);
            }
        }

        @keyframes floatReverse {

            0%,
            100% {
                transform: translateY(0px) rotate(0deg);
            }

            50% {
                transform: translateY(15px) rotate(-2deg);
            }
        }

        .balloon-float {
            animation: float 8s ease-in-out infinite;
        }

        .balloon-float-reverse {
            animation: floatReverse 7s ease-in-out infinite;
        }

        .balloon-float-slow {
            animation: float 10s ease-in-out infinite;
        }

        .feature-card {
            background: var(--bs-body-bg);
            border: 1px solid var(--bs-border-color);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .feature-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05);
        }

        .color-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.25rem 0.75rem;
            border-radius: 2rem;
            font-size: 0.8rem;
        }
    </style>

    <div class="position-relative overflow-hidden">
        <!-- Ballons fond -->
        <div class="position-absolute top-0 start-0 w-100 h-100 overflow-hidden">
            <i class="bi bi-balloon-heart-fill position-absolute balloon-1 balloon-float"></i>
            <i class="bi bi-balloon-heart-fill position-absolute balloon-2 balloon-float-reverse"></i>
            <i class="bi bi-balloon-heart-fill position-absolute balloon-3 balloon-float-slow"></i>
            <i class="bi bi-balloon-heart-fill position-absolute balloon-4 balloon-float"></i>
            <i class="bi bi-balloon-heart-fill position-absolute balloon-5 balloon-float-reverse"></i>
            <i class="bi bi-balloon-heart-fill position-absolute balloon-6 balloon-float-slow"></i>
        </div>

        <!-- Contenu -->
        <div class="container py-5 position-relative">

            <!-- Section principale -->
            <div class="row justify-content-center mb-5">
                <div class="col-12 col-md-8 text-center">
                
                    <h1 class="display-4 fw-bold mb-3" style="color: var(--bs-body-color);">
                        Welcome
                    </h1>

                    <p class="lead mb-4" style="color: var(--bs-secondary-color);">
                        Votre projet commence ici
                    </p>

                    <div class="d-flex gap-3 justify-content-center">
                        <button class="btn btn-primary px-4 py-2 rounded-pill">
                            Commencer
                        </button>
                        <button class="btn btn-outline-secondary px-4 py-2 rounded-pill">
                            En savoir plus
                        </button>
                    </div>
                </div>
            </div>

            <!-- Badges des couleurs -->
            <div class="row justify-content-center mb-5">
                <div class="col-12">
                    <div class="d-flex flex-wrap gap-2 justify-content-center">
                        <span class="color-badge" style="background: var(--bs-primary); color: white;">
                            <i class="bi bi-palette-fill"></i> primary
                        </span>
                        <span class="color-badge" style="background: var(--bs-secondary); color: white;">
                            <i class="bi bi-palette-fill"></i> secondary
                        </span>
                        <span class="color-badge" style="background: var(--bs-success); color: white;">
                            <i class="bi bi-check-circle-fill"></i> success
                        </span>
                        <span class="color-badge" style="background: var(--bs-danger); color: white;">
                            <i class="bi bi-x-circle-fill"></i> danger
                        </span>
                        <span class="color-badge" style="background: var(--bs-warning); color: var(--bs-dark);">
                            <i class="bi bi-exclamation-triangle-fill"></i> warning
                        </span>
                        <span class="color-badge" style="background: var(--bs-info); color: white;">
                            <i class="bi bi-info-circle-fill"></i> info
                        </span>
                        <span class="color-badge" style="background: var(--bs-light); color: var(--bs-dark); border: 1px solid var(--bs-border-color);">
                            <i class="bi bi-sun-fill"></i> light
                        </span>
                        <span class="color-badge" style="background: var(--bs-dark); color: white;">
                            <i class="bi bi-moon-fill"></i> dark
                        </span>
                        <span class="color-badge" style="background: var(--bs-accent, #f59e0b); color: white;">
                            <i class="bi bi-star-fill"></i> accent
                        </span>
                    </div>
                </div>
            </div>

            <button type="button" class="btn btn-primary">Primary</button>
            <button type="button" class="btn btn-secondary">Secondary</button>
            <button type="button" class="btn btn-success">Success</button>
            <button type="button" class="btn btn-danger">Danger</button>
            <button type="button" class="btn btn-warning">Warning</button>
            <button type="button" class="btn btn-info">Info</button>
            <button type="button" class="btn btn-light">Light</button>
            <button type="button" class="btn btn-dark">Dark</button>

            <button type="button" class="btn btn-link">Link</button>

            <!-- Maquettes HTML (templates) -->
            <div class="row justify-content-center mt-5">
                <div class="col-12 col-lg-10">
                    <div class="feature-card rounded-4 p-4">
                        <h5 class="fw-semibold mb-1" style="color: var(--bs-body-color);">
                            <i class="bi bi-file-earmark-code me-1" style="color: var(--bs-primary);"></i>
                            Maquettes HTML
                        </h5>
                        <p class="small mb-3" style="color: var(--bs-secondary-color);">
                            Prévisualisez les maquettes statiques du site SP-CNPI.
                        </p>
                        <div class="d-flex flex-wrap gap-2">
                            <a href="{{ url('/templates/index') }}" target="_blank" class="btn btn-outline-primary btn-sm rounded-pill">
                                <i class="bi bi-house-door me-1"></i> Accueil
                            </a>
                            <a href="{{ url('/templates/apropos') }}" target="_blank" class="btn btn-outline-primary btn-sm rounded-pill">
                                <i class="bi bi-info-circle me-1"></i> À propos
                            </a>
                            <a href="{{ url('/templates/articles') }}" target="_blank" class="btn btn-outline-primary btn-sm rounded-pill">
                                <i class="bi bi-newspaper me-1"></i> Actualités
                            </a>
                            <a href="{{ url('/templates/article-detail') }}" target="_blank" class="btn btn-outline-primary btn-sm rounded-pill">
                                <i class="bi bi-file-text me-1"></i> Détail article
                            </a>
                            <a href="{{ url('/templates/article-list') }}" target="_blank" class="btn btn-outline-secondary btn-sm rounded-pill">
                                <i class="bi bi-table me-1"></i> Liste (admin)
                            </a>
                            <a href="{{ url('/templates/article-form') }}" target="_blank" class="btn btn-outline-secondary btn-sm rounded-pill">
                                <i class="bi bi-pencil-square me-1"></i> Formulaire (admin)
                            </a>
                            <a href="{{ url('/templates/video') }}" target="_blank" class="btn btn-outline-secondary btn-sm rounded-pill">
                                <i class="bi bi-pencil-square me-1"></i> Videos
                            </a>
                            <a href="{{ url('/templates/documents') }}" target="_blank" class="btn btn-outline-secondary btn-sm rounded-pill">
                                <i class="bi bi-pencil-square me-1"></i> Documents
                            </a>
                        </div>
                    </div>
                </div>
            </div>


            <!-- 3 Cards en bas -->
            <div class="row g-4 mt-2">

                <div class="col-md-4">
                    <div class="feature-card rounded-4 p-4 h-100">
                        <div class="mb-3">
                            <i class="bi bi-palette-fill fs-2" style="color: var(--bs-primary);"></i>
                        </div>
                        <h5 class="fw-semibold mb-2" style="color: var(--bs-body-color);">Palette dynamique</h5>
                        <p class="small" style="color: var(--bs-secondary-color);">
                            Modifiez <code>_variables.scss</code> et toutes les couleurs se mettent à jour instantanément.
                        </p>
                        <div class="mt-2">
                            <span class="badge bg-primary">60%</span>
                            <span class="badge bg-secondary">30%</span>
                            <span class="badge bg-info">10%</span>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="feature-card rounded-4 p-4 h-100">
                        <div class="mb-3">
                            <i class="bi bi-bootstrap-fill fs-2" style="color: var(--bs-secondary);"></i>
                        </div>
                        <h5 class="fw-semibold mb-2" style="color: var(--bs-body-color);">Bootstrap 5.3</h5>
                        <p class="small" style="color: var(--bs-secondary-color);">
                            Basé sur Bootstrap 5.3 avec support complet des thèmes clair/sombre.
                        </p>
                        <div class="mt-2">
                            <span class="badge bg-primary">CSS vars</span>
                            <span class="badge bg-secondary">SCSS</span>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="feature-card rounded-4 p-4 h-100">
                        <div class="mb-3">
                            <i class="bi bi-code-square fs-2" style="color: var(--bs-success);"></i>
                        </div>
                        <h5 class="fw-semibold mb-2" style="color: var(--bs-body-color);">Clean & modulable</h5>
                        <p class="small" style="color: var(--bs-secondary-color);">
                            Structure propre, composants réutilisables et design responsive.
                        </p>
                        <div class="mt-2">
                            <span class="badge bg-success">Responsive</span>
                            <span class="badge bg-info">Modulable</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Note subtile -->
            <div class="row mt-5">
                <div class="col-12 text-center">
                    <p class="small" style="color: var(--bs-secondary-color);">
                        <i class="bi bi-droplet-half me-1" style="color: var(--bs-primary);"></i>
                        Règle 60-30-10 appliquée à votre charte graphique
                    </p>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>