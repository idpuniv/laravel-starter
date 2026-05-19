@section('title', 'Dashboard')

<x-admin-layout>
    {{-- Scripts et styles essentiels --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.7.0/chart.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/js/all.min.js"></script>

    <div class="container-fluid px-2 px-md-3 py-4">
        {{-- En-tête --}}
        <div class="row mb-4">
            <div class="col-12">
                <h1 class="h4 mb-2 fw-normal">Tableau de bord</h1>
                <p class="text-secondary small mb-0">Bienvenue ! Voici un aperçu de votre activité récente</p>
            </div>
        </div>

        {{-- Cartes statistiques --}}
        <div class="row g-3 mb-4">
            <div class="col-sm-6 col-lg-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body p-3">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <p class="text-secondary small text-uppercase mb-1">Utilisateurs</p>
                                <h3 class="mb-2 fw-semibold">12 584</h3>
                                <p class="mb-0 small text-success">
                                    <i class="fas fa-arrow-up me-1"></i>+4,3%
                                </p>
                                <small class="text-secondary">vs mois dernier</small>
                            </div>
                            <div class="rounded-circle p-2 bg-primary bg-opacity-10">
                                <i class="fas fa-users text-primary" style="font-size: 1.25rem;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-sm-6 col-lg-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body p-3">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <p class="text-secondary small text-uppercase mb-1">Revenus</p>
                                <h3 class="mb-2 fw-semibold">48 265 $</h3>
                                <p class="mb-0 small text-success">
                                    <i class="fas fa-arrow-up me-1"></i>+12,5%
                                </p>
                                <small class="text-secondary">vs mois dernier</small>
                            </div>
                            <div class="rounded-circle p-2 bg-success bg-opacity-10">
                                <i class="fas fa-dollar-sign text-success" style="font-size: 1.25rem;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-sm-6 col-lg-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body p-3">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <p class="text-secondary small text-uppercase mb-1">Conversion</p>
                                <h3 class="mb-2 fw-semibold">24,8%</h3>
                                <p class="mb-0 small text-danger">
                                    <i class="fas fa-arrow-down me-1"></i>-1,2%
                                </p>
                                <small class="text-secondary">vs mois dernier</small>
                            </div>
                            <div class="rounded-circle p-2 bg-info bg-opacity-10">
                                <i class="fas fa-chart-line text-info" style="font-size: 1.25rem;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-sm-6 col-lg-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body p-3">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <p class="text-secondary small text-uppercase mb-1">Rétention</p>
                                <h3 class="mb-2 fw-semibold">78,2%</h3>
                                <p class="mb-0 small text-success">
                                    <i class="fas fa-arrow-up me-1"></i>+3,7%
                                </p>
                                <small class="text-secondary">vs mois dernier</small>
                            </div>
                            <div class="rounded-circle p-2 bg-warning bg-opacity-10">
                                <i class="fas fa-percentage text-warning" style="font-size: 1.25rem;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Graphiques modernes --}}
        <div class="row g-3 mb-4">
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-transparent border-0 pt-3 pb-0">
                        <h5 class="mb-0 fw-semibold">Performance des ventes</h5>
                    </div>
                    <div class="card-body">
                        <canvas id="salesChart" style="height: 300px;"></canvas>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-transparent border-0 pt-3 pb-0">
                        <h5 class="mb-0 fw-semibold">Sources de trafic</h5>
                    </div>
                    <div class="card-body">
                        <canvas id="trafficChart" style="height: 300px;"></canvas>
                    </div>
                </div>
            </div>
        </div>

        {{-- Graphique des utilisateurs actifs --}}
        <div class="row g-3 mb-4">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-transparent border-0 pt-3 pb-0">
                        <h5 class="mb-0 fw-semibold">Utilisateurs actifs (7 derniers jours)</h5>
                    </div>
                    <div class="card-body">
                        <canvas id="usersChart" style="height: 300px;"></canvas>
                    </div>
                </div>
            </div>
        </div>

        {{-- Dernières transactions --}}
        <div class="row g-3">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-transparent border-0 pt-3 d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 fw-semibold">Dernières transactions</h5>
                        <button class="btn btn-outline-secondary btn-sm">
                            <i class="fas fa-download me-1"></i>Exporter
                        </button>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead>
                                    <tr>
                                        <th class="border-0">ID</th>
                                        <th class="border-0">Client</th>
                                        <th class="border-0">Date</th>
                                        <th class="border-0">Montant</th>
                                        <th class="border-0">Statut</th>
                                        <th class="border-0">Progression</th>
                                        <th class="border-0">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="fw-semibold">#5842</td>
                                        <td>Sophie Martin</td>
                                        <td>12 Nov. 2024</td>
                                        <td class="fw-semibold">1 250 $</td>
                                        <td><span class="badge bg-success">Complété</span></td>
                                        <td style="width: 100px;">
                                            <div class="progress" style="height: 6px;">
                                                <div class="progress-bar bg-success" style="width: 100%"></div>
                                            </div>
                                        </td>
                                        <td>
                                            <button class="btn btn-sm btn-link text-secondary text-decoration-none p-0">Détails</button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="fw-semibold">#5841</td>
                                        <td>Thomas Bernard</td>
                                        <td>11 Nov. 2024</td>
                                        <td class="fw-semibold">850 $</td>
                                        <td><span class="badge bg-warning">En attente</span></td>
                                        <td style="width: 100px;">
                                            <div class="progress" style="height: 6px;">
                                                <div class="progress-bar bg-warning" style="width: 60%"></div>
                                            </div>
                                        </td>
                                        <td>
                                            <button class="btn btn-sm btn-link text-secondary text-decoration-none p-0">Détails</button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="fw-semibold">#5840</td>
                                        <td>Marie Dubois</td>
                                        <td>10 Nov. 2024</td>
                                        <td class="fw-semibold">2 450 $</td>
                                        <td><span class="badge bg-success">Complété</span></td>
                                        <td style="width: 100px;">
                                            <div class="progress" style="height: 6px;">
                                                <div class="progress-bar bg-success" style="width: 100%"></div>
                                            </div>
                                        </td>
                                        <td>
                                            <button class="btn btn-sm btn-link text-secondary text-decoration-none p-0">Détails</button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="fw-semibold">#5839</td>
                                        <td>Jean Lefebvre</td>
                                        <td>9 Nov. 2024</td>
                                        <td class="fw-semibold">520 $</td>
                                        <td><span class="badge bg-secondary">Annulé</span></td>
                                        <td style="width: 100px;">
                                            <div class="progress" style="height: 6px;">
                                                <div class="progress-bar bg-secondary" style="width: 0%"></div>
                                            </div>
                                        </td>
                                        <td>
                                            <button class="btn btn-sm btn-link text-secondary text-decoration-none p-0">Détails</button>
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

    <script>
        // Graphique des ventes - Style moderne avec dégradé
        const salesCtx = document.getElementById('salesChart').getContext('2d');
        const gradient1 = salesCtx.createLinearGradient(0, 0, 0, 300);
        gradient1.addColorStop(0, 'rgba(59, 130, 246, 0.8)');
        gradient1.addColorStop(1, 'rgba(59, 130, 246, 0.2)');
        
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
                    tooltip: { backgroundColor: '#1f2937', titleColor: '#f3f4f6', bodyColor: '#d1d5db', padding: 10, cornerRadius: 8 }
                },
                scales: {
                    y: { grid: { display: true, drawBorder: false, color: 'rgba(0,0,0,0.05)' }, ticks: { stepSize: 30 } },
                    x: { grid: { display: false } }
                }
            }
        });

        // Graphique des sources - Donut moderne avec animation
        new Chart(document.getElementById('trafficChart'), {
            type: 'doughnut',
            data: {
                labels: ['Organique', 'Réseaux sociaux', 'Email', 'Direct', 'Référencement'],
                datasets: [{
                    data: [35, 25, 20, 12, 8],
                    backgroundColor: ['#3b82f6', '#ef4444', '#10b981', '#06b6d4', '#6b7280'],
                    borderWidth: 0,
                    hoverOffset: 8,
                    cutout: '65%'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'bottom', labels: { usePointStyle: true, pointStyle: 'circle', boxWidth: 8, padding: 15 } },
                    tooltip: { backgroundColor: '#1f2937', padding: 10, cornerRadius: 8 }
                }
            }
        });

        // Graphique des utilisateurs - Ligne épurée avec zone dégradée
        const usersCtx = document.getElementById('usersChart').getContext('2d');
        const gradient2 = usersCtx.createLinearGradient(0, 0, 0, 300);
        gradient2.addColorStop(0, 'rgba(59, 130, 246, 0.3)');
        gradient2.addColorStop(1, 'rgba(59, 130, 246, 0.01)');
        
        new Chart(usersCtx, {
            type: 'line',
            data: {
                labels: ['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi', 'Dimanche'],
                datasets: [{
                    label: 'Utilisateurs actifs',
                    data: [1200, 1900, 1500, 2100, 1800, 2500, 2200],
                    borderColor: '#3b82f6',
                    borderWidth: 2.5,
                    backgroundColor: gradient2,
                    fill: true,
                    tension: 0.4,
                    pointRadius: 3,
                    pointHoverRadius: 6,
                    pointBackgroundColor: '#3b82f6',
                    pointBorderColor: '#ffffff',
                    pointBorderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: { backgroundColor: '#1f2937', padding: 10, cornerRadius: 8 }
                },
                scales: {
                    y: { grid: { display: true, drawBorder: false, color: 'rgba(0,0,0,0.05)' }, ticks: { stepSize: 500 } },
                    x: { grid: { display: false } }
                }
            }
        });
    </script>
</x-admin-layout>