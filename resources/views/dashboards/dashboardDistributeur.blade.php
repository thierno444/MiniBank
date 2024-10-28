@extends('layouts.sidebar-navbarD')

@section('content')
<div class="container-fluid py-4">
    <!-- Quick Stats Row -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 gradient-primary shadow-sm">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="text-white mb-1">Solde Actuel</h6>
                            <h4 class="text-white mb-0" id="solde">
                                {{ number_format($solde, 0, ',', ' ') }} FCFA
                            </h4>
                        </div>
                        <button class="btn btn-light btn-sm rounded-circle" id="toggleSolde">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                    <div class="mt-3">
                        <small class="text-white opacity-75">
                            N° Compte: {{ $numCompte }}
                        </small>
                    </div>
                </div>
            </div>
        </div>
        


        <div class="col-xl-3 col-md-6">
            <div class="card border-0 gradient-info shadow-sm">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h6 class="text-white mb-1">Cumul Disponible</h6>
                            <h4 class="text-white mb-0">
                                {{ number_format($plafondsCompte['cumul_maximum_restant'], 0, ',', ' ') }} FCFA
                            </h4>
                        </div>
                        <div class="ms-3">
                            <span class="bg-white bg-opacity-25 rounded-circle p-2">
                                <i class="fas fa-wallet text-white"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Row -->
    <div class="row">
        <!-- Transactions Section -->
        <div class="col-xl-8 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Transactions Récentes</h5>
                        <div class="dropdown">
                            <button class="btn btn-light btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                <i class="fas fa-filter me-1"></i> Filtrer
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="#" data-filter="all">Toutes</a></li>
                                <li><a class="dropdown-item" href="#" data-filter="depot">Dépôts</a></li>
                                <li><a class="dropdown-item" href="#" data-filter="retrait">Retraits</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="card-body px-0 pb-0">
                    @if ($recentTransactions->isEmpty())
                        <div class="text-center py-5">
                            <img src="/images/no-data.svg" alt="Aucune transaction" class="mb-3" style="width: 120px;">
                            <p class="text-muted">Aucune transaction récente</p>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover" id="transactionsTable">
                                <thead>
                                    <tr>
                                        <th class="ps-4">Bénéficiaire</th>
                                        <th>Type</th>
                                        <th>Montant</th>
                                        <th>Date</th>
                                        <th class="pe-4">Statut</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($recentTransactions as $transaction)
                                    <tr class="transaction-row" data-type="{{ $transaction->type }}">
                                        <td class="ps-4">
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-circle bg-light me-2">
                                                    {{ substr(optional($transaction->receveur)->prenom ?? 'U', 0, 1) }}
                                                </div>
                                                <div>
                                                    <div class="fw-medium">
                                                        {{ optional($transaction->receveur)->nom ?? 'Inconnu' }}
                                                        {{ optional($transaction->receveur)->prenom ?? '' }}
                                                    </div>
                                                    <small class="text-muted">
                                                        ID: {{ $transaction->reference }}
                                                    </small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge bg-{{ $transaction->type == 'depot' ? 'success' : 'danger' }} bg-opacity-10 text-{{ $transaction->type == 'depot' ? 'success' : 'danger' }}">
                                                {{ ucfirst($transaction->type) }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="fw-medium">
                                                {{ number_format($transaction->montant, 0, ',', ' ') }} FCFA
                                            </span>
                                        </td>
                                        <td>
                                            <div>{{ $transaction->created_at->format('d/m/Y') }}</div>
                                            <small class="text-muted">
                                                {{ $transaction->created_at->format('H:i') }}
                                            </small>
                                        </td>
                                        <td class="pe-4">
                                            <span class="badge bg-success bg-opacity-10 text-success">
                                                Succès
                                            </span>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Account Limits Section -->
        <div class="col-xl-4 mb-4">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="mb-0">Plafonds du Compte</h5>
                </div>
                <div class="card-body">
                    <div class="limit-item mb-4">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Solde Maximum</span>
                            <span class="fw-medium">
                                {{ number_format($plafondsCompte['solde_maximum'], 0, ',', ' ') }} FCFA
                            </span>
                        </div>
                        <div class="progress" style="height: 6px;">
                            @php
                                $soldeRatio = ($solde / $plafondsCompte['solde_maximum']) * 100;
                            @endphp
                            <div class="progress-bar bg-primary" style="width: {{ $soldeRatio }}%"></div>
                        </div>
                    </div>

                    <div class="limit-item mb-4">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Cumul Mensuel</span>
                            <span class="fw-medium">
                                {{ number_format($plafondsCompte['cumul_mensuel_maximum'], 0, ',', ' ') }} FCFA
                            </span>
                        </div>
                        <div class="progress" style="height: 6px;">
                            @php
                                $cumulRatio = (($plafondsCompte['cumul_mensuel_maximum'] - $plafondsCompte['cumul_maximum_restant']) / $plafondsCompte['cumul_mensuel_maximum']) * 100;
                            @endphp
                            <div class="progress-bar bg-success" style="width: {{ $cumulRatio }}%"></div>
                        </div>
                    </div>

                    <div class="alert alert-info border-0 d-flex align-items-center" role="alert">
                        <i class="fas fa-info-circle me-2"></i>
                        <div>
                            Cumul restant ce mois : 
                            <strong>
                                {{ number_format($plafondsCompte['cumul_maximum_restant'], 0, ',', ' ') }} FCFA
                            </strong>
                        </div>
                    </div>
                </div>
            </div>


        </div>
    </div>

    <!-- Chart Section -->
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Activités Mensuelles</h5>
                        <div class="btn-group btn-group-sm">
                            <button type="button" class="btn btn-outline-primary active" data-period="month">Mois</button>
                            <button type="button" class="btn btn-outline-primary" data-period="year">Année</button>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div style="height: 400px;">
                        <canvas id="monthlyActivitiesChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Custom Styles -->
<style>
.gradient-primary {
    background: linear-gradient(45deg, #4e73df, #224abe);
}

.gradient-success {
    background: linear-gradient(45deg, #1cc88a, #13855c);
}

.gradient-danger {
    background: linear-gradient(45deg, #e74a3b, #be2617);
}

.gradient-info {
    background: linear-gradient(45deg, #36b9cc, #258391);
}

.avatar-circle {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 500;
}

.limit-item .progress {
    border-radius: 10px;
    background-color: #e9ecef;
}

.limit-item .progress-bar {
    border-radius: 10px;
}
</style>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Toggle Solde Visibility
    const soldeElement = document.getElementById('solde');
    const toggleButton = document.getElementById('toggleSolde');
    let soldeVisible = true;

    toggleButton.addEventListener('click', function() {
        soldeVisible = !soldeVisible;
        soldeElement.style.visibility = soldeVisible ? 'visible' : 'hidden';
        toggleButton.querySelector('i').className = soldeVisible ? 'fas fa-eye' : 'fas fa-eye-slash';
    });

    // Transactions Filter
    const filterButtons = document.querySelectorAll('[data-filter]');
    const transactionRows = document.querySelectorAll('.transaction-row');

    filterButtons.forEach(button => {
        button.addEventListener('click', (e) => {
            e.preventDefault();
            const filterValue = button.dataset.filter;
            
            transactionRows.forEach(row => {
                if (filterValue === 'all' || row.dataset.type === filterValue) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });

            // Update active state of filter buttons
            filterButtons.forEach(btn => btn.classList.remove('active'));
            button.classList.add('active');
        });
    });

    // Chart Configuration
    const ctx = document.getElementById('monthlyActivitiesChart').getContext('2d');
    let currentChart;

    const createChart = (data, labels) => {
        if (currentChart) {
            currentChart.destroy();
        }

        currentChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [
                    {
                        label: 'Dépôts',
                        data: Object.values(data.deposit),
                        backgroundColor: 'rgba(28, 200, 138, 0.6)',
                        borderColor: '#1cc88a',
                        borderWidth: 1,
                        borderRadius: 4,
                        barThickness: 12
                    },
                    {
                        label: 'Retraits',
                        data: Object.values(data.withdraw),
                        backgroundColor: 'rgba(231, 74, 59, 0.6)',
                        borderColor: '#e74a3b',
                        borderWidth: 1,
                        borderRadius: 4,
                        barThickness: 12
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: {
                    intersect: false,
                    mode: 'index'
                },
                plugins: {
                    legend: {
                        position: 'top',
                        labels: {
                            usePointStyle: true,
                            padding: 20
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(255, 255, 255, 0.9)',
                        titleColor: '#6e707e',
                        bodyColor: '#6e707e',
                        borderColor: '#e3e6f0',
                        borderWidth: 1,
                        padding: 12,
                        displayColors: true,
                        callbacks: {
                            label: function(context) {
                                return context.dataset.label + ': ' + 
                                       context.parsed.y.toLocaleString() + ' FCFA';
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        grid: {
                            display: false
                        }
                    },
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return value.toLocaleString() + ' FCFA';
                            }
                        }
                    }
                }
            }
        });
    };

    // Initial chart creation
    createChart(@json($dashboardData), Object.keys(@json($dashboardData['deposit'])));

    // Period Toggle
    const periodButtons = document.querySelectorAll('[data-period]');
    periodButtons.forEach(button => {
        button.addEventListener('click', async () => {
            const period = button.dataset.period;
            try {
                const response = await fetch(`/api/dashboard-data/${period}`);
                const data = await response.json();
                createChart(data, Object.keys(data.deposit));
                
                // Update active state
                periodButtons.forEach(btn => btn.classList.remove('active'));
                button.classList.add('active');
            } catch (error) {
                console.error('Erreur lors du chargement des données:', error);
            }
        });
    });

    // Auto refresh every 5 minutes
    setInterval(async () => {
        try {
            const response = await fetch('/api/dashboard-data/refresh');
            const data = await response.json();
            // Update solde
            document.getElementById('solde').textContent = 
                `${data.solde.toLocaleString()} FCFA`;
            // Refresh transactions table
            // ... code pour rafraîchir la table ...
        } catch (error) {
            console.error('Erreur lors du rafraîchissement:', error);
        }
    }, 300000);
});
</script>
@endsection