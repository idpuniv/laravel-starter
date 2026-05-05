  {{-- @section('content-header')
      <div>
          <h1 class="h3 fw-semibold mb-1">Dashboard</h1>
          <p class="text-secondary mb-0">Vue d'ensemble de votre activité</p>
      </div>
  @endsection --}}

  @section('title', 'Dashboard')

  <x-admin-layout>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.7.0/chart.min.js"></script>
    <style>
        .chart-container {
            height: 200px;
        }

        .stat-card {
            min-height: 80px;
        }

        .compact-table th,
        .compact-table td {
            padding: 0.5rem;
            font-size: 0.85rem;
        }

        .status-badge {
            font-size: 0.75rem;
            padding: 0.25rem 0.5rem;
        }
    </style>

        <div class="container-fluid p-2 p-md-3">
            <!-- Header -->
            <div class="row mb-3">
                <div class="col-12">
                    <h5 class="text-primary mb-1">Dashboard Analytics</h5>
                    <p class="text-muted small mb-0">Vue d'ensemble des performances</p>
                </div>
            </div>

            <!-- Cartes statistiques -->
            <div class="row mb-3">
                <div class="col-sm-6 col-lg-3 mb-2">
                    <div class="card border-left-primary stat-card h-100">
                        <div class="card-body p-2">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1">
                                    <div class="text-primary small font-weight-bold text-uppercase">Utilisateurs</div>
                                    <div class="h6 mb-0 font-weight-bold">12,584</div>
                                    <div class="text-success small">
                                        <i class="fas fa-arrow-up fa-xs"></i> 4.3% vs mois dernier
                                    </div>
                                </div>
                                <div class="flex-shrink-0">
                                    <i class="fas fa-users text-primary fa-lg"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-sm-6 col-lg-3 mb-2">
                    <div class="card border-left-success stat-card h-100">
                        <div class="card-body p-2">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1">
                                    <div class="text-success small font-weight-bold text-uppercase">Revenus</div>
                                    <div class="h6 mb-0 font-weight-bold">$48,265</div>
                                    <div class="text-success small">
                                        <i class="fas fa-arrow-up fa-xs"></i> 12.5% vs mois dernier
                                    </div>
                                </div>
                                <div class="flex-shrink-0">
                                    <i class="fas fa-dollar-sign text-success fa-lg"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-sm-6 col-lg-3 mb-2">
                    <div class="card border-left-info stat-card h-100">
                        <div class="card-body p-2">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1">
                                    <div class="text-info small font-weight-bold text-uppercase">Conversion</div>
                                    <div class="h6 mb-0 font-weight-bold">24.8%</div>
                                    <div class="text-danger small">
                                        <i class="fas fa-arrow-down fa-xs"></i> 1.2% vs mois dernier
                                    </div>
                                </div>
                                <div class="flex-shrink-0">
                                    <i class="fas fa-percentage text-info fa-lg"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-sm-6 col-lg-3 mb-2">
                    <div class="card border-left-warning stat-card h-100">
                        <div class="card-body p-2">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1">
                                    <div class="text-warning small font-weight-bold text-uppercase">Rétention</div>
                                    <div class="h6 mb-0 font-weight-bold">78.2%</div>
                                    <div class="text-success small">
                                        <i class="fas fa-arrow-up fa-xs"></i> 3.7% vs mois dernier
                                    </div>
                                </div>
                                <div class="flex-shrink-0">
                                    <i class="fas fa-chart-line text-warning fa-lg"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Graphiques -->
            <div class="row mb-3">
                <div class="col-lg-8 mb-3">
                    <div class="card h-100">
                        <div class="card-header py-2">
                            <h6 class="mb-0 text-primary font-weight-bold">Performance des ventes</h6>
                        </div>
                        <div class="card-body p-2">
                            <div class="chart-container">
                                <canvas id="histogramChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 mb-3">
                    <div class="card h-100">
                        <div class="card-header py-2">
                            <h6 class="mb-0 text-primary font-weight-bold">Sources de trafic</h6>
                        </div>
                        <div class="card-body p-2">
                            <div class="chart-container">
                                <canvas id="pieChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Graphique ligne -->
            <div class="row mb-3">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header text-primary py-2">
                            <h6 class="mb-0 font-weight-bold">Utilisateurs actifs</h6>
                        </div>
                        <div class="card-body p-2">
                            <div class="chart-container">
                                <canvas id="lineChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tableau -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header py-2 d-flex justify-content-between align-items-center">
                            <h6 class="mb-0 text-primary font-weight-bold">Dernières transactions</h6>
                            <button class="btn btn-primary btn-sm">Exporter</button>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover mb-0 compact-table">
                                    <thead class="thead-light">
                                        <tr>
                                            <th class="border-0">ID</th>
                                            <th class="border-0">Client</th>
                                            <th class="border-0">Date</th>
                                            <th class="border-0">Montant</th>
                                            <th class="border-0">Statut</th>
                                            <th class="border-0">Progr.</th>
                                            <th class="border-0">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td class="font-weight-bold">#5842</td>
                                            <td>Sophie Martin</td>
                                            <td>12 Nov</td>
                                            <td>$1,250</td>
                                            <td>
                                                <span class="badge badge-success status-badge">OK</span>
                                            </td>
                                            <td>
                                                <div class="progress" style="height: 4px;">
                                                    <div class="progress-bar bg-success" style="width: 100%"></div>
                                                </div>
                                            </td>
                                            <td>
                                                <button class="btn btn-outline-primary btn-sm">Voir</button>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="font-weight-bold">#5841</td>
                                            <td>Thomas Bernard</td>
                                            <td>11 Nov</td>
                                            <td>$850</td>
                                            <td>
                                                <span class="badge badge-warning status-badge">En att.</span>
                                            </td>
                                            <td>
                                                <div class="progress" style="height: 4px;">
                                                    <div class="progress-bar bg-warning" style="width: 60%"></div>
                                                </div>
                                            </td>
                                            <td>
                                                <button class="btn btn-outline-primary btn-sm">Voir</button>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="font-weight-bold">#5840</td>
                                            <td>Marie Dubois</td>
                                            <td>10 Nov</td>
                                            <td>$2,450</td>
                                            <td>
                                                <span class="badge badge-success status-badge">OK</span>
                                            </td>
                                            <td>
                                                <div class="progress" style="height: 4px;">
                                                    <div class="progress-bar bg-success" style="width: 100%"></div>
                                                </div>
                                            </td>
                                            <td>
                                                <button class="btn btn-outline-primary btn-sm">Voir</button>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="font-weight-bold">#5839</td>
                                            <td>Jean Lefebvre</td>
                                            <td>09 Nov</td>
                                            <td>$520</td>
                                            <td>
                                                <span class="badge badge-secondary status-badge">Annul.</span>
                                            </td>
                                            <td>
                                                <div class="progress" style="height: 4px;">
                                                    <div class="progress-bar bg-secondary" style="width: 0%"></div>
                                                </div>
                                            </td>
                                            <td>
                                                <button class="btn btn-outline-primary btn-sm">Voir</button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Font Awesome via Cloudflare -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/js/all.min.js"></script>

        <script>
            // Histogramme
            const histogramCtx = document.getElementById('histogramChart').getContext('2d');
            const histogramChart = new Chart(histogramCtx, {
                type: 'bar',
                data: {
                    labels: ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Juin', 'Juil', 'Août', 'Sep', 'Oct', 'Nov', 'Déc'],
                    datasets: [{
                        label: 'Ventes (k€)',
                        data: [65, 59, 80, 81, 56, 55, 40, 72, 88, 94, 105, 120],
                        backgroundColor: '#1e3a8a',
                        borderColor: '#1e40af',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    },
                    plugins: {
                        legend: {
                            display: false
                        }
                    }
                }
            });

            // Camembert
            const pieCtx = document.getElementById('pieChart').getContext('2d');
            const pieChart = new Chart(pieCtx, {
                type: 'doughnut',
                data: {
                    labels: ['Organique', 'Social', 'Email', 'Direct', 'Ref.'],
                    datasets: [{
                        data: [35, 25, 20, 12, 8],
                        backgroundColor: ['#1e3a8a', '#dc2626', '#059669', '#1e40af', '#10b981'],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '60%',
                    plugins: {
                        legend: {
                            position: 'bottom'
                        }
                    }
                }
            });

            // Graphique ligne
            const lineCtx = document.getElementById('lineChart').getContext('2d');

            const gradient = lineCtx.createLinearGradient(0, 0, 0, 300);
            gradient.addColorStop(0, 'rgba(30, 58, 138, 0.3)');
            gradient.addColorStop(1, 'rgba(30, 64, 175, 0.05)');

            const lineChart = new Chart(lineCtx, {
                type: 'line',
                data: {
                    labels: ['Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam', 'Dim'],
                    datasets: [{
                        label: 'Utilisateurs actifs',
                        data: [1200, 1900, 1500, 2100, 1800, 2500, 2200],
                        backgroundColor: gradient,
                        borderColor: '#1e3a8a',
                        borderWidth: 2,
                        fill: true,
                        tension: 0.4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    },
                    plugins: {
                        legend: {
                            display: false
                        }
                    }
                }
            });
        </script>
</x-admin-layout>

