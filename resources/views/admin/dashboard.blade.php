@section('title', __('titles.dashboard.index'))

<x-admin-layout>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.7.0/chart.min.js"></script>

    <div class="container-fluid px-2 px-md-3 py-4">

        {{-- Header --}}
        <div class="row mb-4">
            <div class="col-12">
                <h1 class="h4 mb-2 fw-normal">
                    {{ __('Dashboard') }}
                </h1>
                <p class="text-body-secondary small mb-0">
                    {{ __('Bienvenue ! Voici un aperçu de votre activité récente') }}
                </p>
            </div>
        </div>

        {{-- Stats Cards --}}
        <div class="row g-3 mb-4">
            <div class="col-sm-6 col-lg-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body p-3">
                        <p class="text-body-secondary small text-uppercase mb-1">{{ __('Utilisateurs') }}</p>
                        <h3 class="mb-2 fw-semibold">12 584</h3>
                        <p class="mb-0 small text-success"><i class="fas fa-arrow-up me-1"></i>+4,3%</p>
                        <small class="text-body-secondary">{{ __('vs mois dernier') }}</small>
                    </div>
                </div>
            </div>

            <div class="col-sm-6 col-lg-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body p-3">
                        <p class="text-body-secondary small text-uppercase mb-1">{{ __('Revenus') }}</p>
                        <h3 class="mb-2 fw-semibold">48 265 $</h3>
                        <small class="text-body-secondary">{{ __('vs mois dernier') }}</small>
                    </div>
                </div>
            </div>

            <div class="col-sm-6 col-lg-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body p-3">
                        <p class="text-body-secondary small text-uppercase mb-1">{{ __('Conversion') }}</p>
                        <h3 class="mb-2 fw-semibold">24,8%</h3>
                        <small class="text-body-secondary">{{ __('vs mois dernier') }}</small>
                    </div>
                </div>
            </div>

            <div class="col-sm-6 col-lg-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body p-3">
                        <p class="text-body-secondary small text-uppercase mb-1">{{ __('Rétention') }}</p>
                        <h3 class="mb-2 fw-semibold">78,2%</h3>
                        <small class="text-body-secondary">{{ __('vs mois dernier') }}</small>
                    </div>
                </div>
            </div>
        </div>

        {{-- Charts --}}
        <div class="row g-3 mb-4">
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-transparent border-0 pt-3 pb-0">
                        <h5 class="mb-0 fw-semibold">{{ __('Performance des ventes') }}</h5>
                    </div>
                    <div class="card-body">
                        <canvas id="salesChart" style="height: 300px;"></canvas>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-transparent border-0 pt-3 pb-0">
                        <h5 class="mb-0 fw-semibold">{{ __('Sources de trafic') }}</h5>
                    </div>
                    <div class="card-body">
                        <canvas id="trafficChart" style="height: 300px;"></canvas>
                    </div>
                </div>
            </div>
        </div>

        {{-- Users chart --}}
        <div class="row g-3 mb-4">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-transparent border-0 pt-3 pb-0">
                        <h5 class="mb-0 fw-semibold">{{ __('Utilisateurs actifs (7 derniers jours)') }}</h5>
                    </div>
                    <div class="card-body">
                        <canvas id="usersChart" style="height: 300px;"></canvas>
                    </div>
                </div>
            </div>
        </div>

        {{-- Table --}}
        <div class="row g-3">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-transparent border-0 pt-3 d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 fw-semibold">{{ __('Dernières transactions') }}</h5>
                        <button class="btn btn-outline-secondary btn-sm">
                            <i class="fas fa-download me-1"></i>
                            {{ __('Exporter') }}
                        </button>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>{{ __('Client') }}</th>
                                        <th>{{ __('Date') }}</th>
                                        <th>{{ __('Montant') }}</th>
                                        <th>{{ __('Statut') }}</th>
                                        <th>{{ __('Progression') }}</th>
                                        <th>{{ __('Actions') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>#5842</td>
                                        <td>Sophie Martin</td>
                                        <td>12 Nov. 2024</td>
                                        <td>1 250 $</td>
                                        <td><span class="badge bg-success">{{ __('Complété') }}</span></td>
                                        <td></td>
                                        <td>{{ __('Détails') }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

</x-admin-layout>