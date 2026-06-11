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

    <script>
        // Helper function to get Bootstrap CSS variable value
        function getCssVar(variable) {
            return getComputedStyle(document.documentElement).getPropertyValue(variable).trim();
        }

        // Get theme colors from Bootstrap CSS variables
        const primaryColor = getCssVar('--bs-primary');
        const successColor = getCssVar('--bs-success');
        const dangerColor = getCssVar('--bs-danger');
        const infoColor = getCssVar('--bs-info');
        const warningColor = getCssVar('--bs-warning');
        const secondaryColor = getCssVar('--bs-secondary');
        const bodyColor = getCssVar('--bs-body-color');
        const bodyBg = getCssVar('--bs-body-bg');
        const borderColor = getCssVar('--bs-border-color');

        // Sales Chart (Bar)
        const salesCtx = document.getElementById('salesChart').getContext('2d');
        const gradient1 = salesCtx.createLinearGradient(0, 0, 0, 300);
        gradient1.addColorStop(0, primaryColor + 'cc');
        gradient1.addColorStop(1, primaryColor + '33');
        
        new Chart(salesCtx, {
            type: 'bar',
            data: {
                labels: ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Juin', 'Juil', 'Août', 'Sep', 'Oct', 'Nov', 'Déc'],
                datasets: [{
                    label: 'Ventes (k$)',
                    data: [65, 59, 80, 81, 56, 55, 40, 72, 88, 94, 105, 120],
                    backgroundColor: gradient1,
                    borderRadius: 8,
                    barPercentage: 0.65,
                    categoryPercentage: 0.8
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: { 
                        backgroundColor: bodyColor,
                        titleColor: bodyBg,
                        bodyColor: secondaryColor,
                        padding: 10,
                        cornerRadius: 8
                    }
                },
                scales: {
                    y: { 
                        grid: { display: true, drawBorder: false, color: borderColor + '33' }, 
                        ticks: { stepSize: 30, color: secondaryColor }
                    },
                    x: { 
                        grid: { display: false },
                        ticks: { color: secondaryColor }
                    }
                }
            }
        });

        // Traffic Sources Chart (Doughnut)
        new Chart(document.getElementById('trafficChart'), {
            type: 'doughnut',
            data: {
                labels: ['Organique', 'Réseaux sociaux', 'Email', 'Direct', 'Référencement'],
                datasets: [{
                    data: [35, 25, 20, 12, 8],
                    backgroundColor: [primaryColor, dangerColor, successColor, infoColor, secondaryColor],
                    borderWidth: 0,
                    hoverOffset: 8,
                    cutout: '65%'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { 
                        position: 'bottom', 
                        labels: { 
                            usePointStyle: true, 
                            pointStyle: 'circle', 
                            boxWidth: 8, 
                            padding: 15,
                            color: bodyColor
                        } 
                    },
                    tooltip: { 
                        backgroundColor: bodyColor,
                        titleColor: bodyBg,
                        bodyColor: secondaryColor,
                        padding: 10, 
                        cornerRadius: 8 
                    }
                }
            }
        });

        // Users Chart (Line)
        const usersCtx = document.getElementById('usersChart').getContext('2d');
        const gradient2 = usersCtx.createLinearGradient(0, 0, 0, 300);
        gradient2.addColorStop(0, primaryColor + '4d');
        gradient2.addColorStop(1, primaryColor + '03');
        
        new Chart(usersCtx, {
            type: 'line',
            data: {
                labels: ['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi', 'Dimanche'],
                datasets: [{
                    label: 'Utilisateurs actifs',
                    data: [1200, 1900, 1500, 2100, 1800, 2500, 2200],
                    borderColor: primaryColor,
                    borderWidth: 2.5,
                    backgroundColor: gradient2,
                    fill: true,
                    tension: 0.4,
                    pointRadius: 3,
                    pointHoverRadius: 6,
                    pointBackgroundColor: primaryColor,
                    pointBorderColor: bodyBg,
                    pointBorderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: { 
                        backgroundColor: bodyColor,
                        titleColor: bodyBg,
                        bodyColor: secondaryColor,
                        padding: 10, 
                        cornerRadius: 8 
                    }
                },
                scales: {
                    y: { 
                        grid: { display: true, drawBorder: false, color: borderColor + '33' }, 
                        ticks: { stepSize: 500, color: secondaryColor }
                    },
                    x: { 
                        grid: { display: false },
                        ticks: { color: secondaryColor }
                    }
                }
            }
        });
    </script>
</x-admin-layout>