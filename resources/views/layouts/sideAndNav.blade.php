@section('content')
<div class="container-fluid py-4">
    <!-- Vue d'ensemble du compte -->
    <div class="row g-4 mb-4">
        <!-- Carte du solde -->
        <div class="col-12 col-md-6 col-lg-4">
            <div class="card border-0 shadow-lg h-100 bg-gradient-primary text-white">
                <div class="card-body position-relative">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <h6 class="text-white-50 mb-3">
                                <i class="fas fa-wallet me-2"></i>Solde disponible
                            </h6>
                            <div class="d-flex align-items-baseline">
                                <h3 class="display-6 mb-0 me-2" id="solde">
                                    {{ number_format($solde, 0, ',', ' ') }}
                                </h3>
                                <span>FCFA</span>
                            </div>
                            <p class="mt-2 mb-0 opacity-75">
                                N° Compte: <strong>{{ $numCompte }}</strong>
                            </p>
                        </div>
                        <button class="btn btn-light btn-sm rounded-circle shadow-sm" id="toggleSolde">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Carte des plafonds -->
        <div class="col-12 col-md-6 col-lg-4">
            <div class="card border-0 shadow-lg h-100">
                <div class="card-header bg-white border-0 py-3">
                    <h6 class="mb-0 text-primary">
                        <i class="fas fa-chart-line me-2"></i>Plafonds du Compte
                    </h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <span class="text-muted">Solde Maximum</span>
                            <strong>{{ number_format($plafondsCompte['solde_maximum'], 0, ',', ' ') }} FCFA</strong>
                        </div>
                        <div class="progress" style="height: 6px;">
                            <div class="progress-bar bg-success" role="progressbar" 
                                 style="width: {{ ($solde / $plafondsCompte['solde_maximum']) * 100 }}%">
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <span class="text-muted">Cumul Mensuel</span>
                            <strong>{{ number_format($plafondsCompte['cumul_mensuel_maximum'], 0, ',', ' ') }} FCFA</strong>
                        </div>
                        <div class="progress" style="height: 6px;">
                            <div class="progress-bar bg-warning" role="progressbar" 
                                 style="width: {{ (($plafondsCompte['cumul_mensuel_maximum'] - $plafondsCompte['cumul_maximum_restant']) / $plafondsCompte['cumul_mensuel_maximum']) * 100 }}%">
                            </div>
                        </div>
                    </div>
                    <div class="text-center mt-3">
                        <span class="badge bg-success-subtle text-success px-3 py-2">
                            Restant: {{ number_format($plafondsCompte['cumul_maximum_restant'], 0, ',', ' ') }} FCFA
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Actions rapides -->
        <div class="col-12 col-md-6 col-lg-4">
            <div class="card border-0 shadow-lg h-100">
                <div class="card-header bg-white border-0 py-3">
                    <h6 class="mb-0 text-primary">
                        <i class="fas fa-bolt me-2"></i>Actions Rapides
                    </h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <button class="btn btn-primary">
                            <i class="fas fa-paper-plane me-2"></i>Nouveau Transfert
                        </button>
                        <button class="btn btn-outline-primary">
                            <i class="fas fa-history me-2"></i>Historique Complet
                        </button>
                        <button class="btn btn-outline-primary">
                            <i class="fas fa-file-invoice me-2"></i>Relevé de Compte
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Transactions récentes et graphique -->
    <div class="row g-4">
        <!-- Transactions récentes -->
        <div class="col-12 col-lg-7">
            <div class="card border-0 shadow-lg">
                <div class="card-header bg-white border-0 py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6 class="mb-0 text-primary">
                            <i class="fas fa-exchange-alt me-2"></i>Transactions Récentes
                        </h6>
                        <div class="btn-group">
                            <button class="btn btn-sm btn-outline-primary active">Aujourd'hui</button>
                            <button class="btn btn-sm btn-outline-primary">Cette semaine</button>
                        </div>
                    </div>
                </div>
                <div class="card-body p-0">
                    @if ($recentTransactions->isEmpty())
                        <div class="text-center py-5">
                            <img src="/assets/images/no-data.svg" alt="Aucune transaction" class="mb-3" style="width: 120px;">
                            <p class="text-muted mb-0">Aucune transaction récente à afficher</p>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="border-0">Bénéficiaire</th>
                                        <th class="border-0">Montant</th>
                                        <th class="border-0">Date</th>
                                        <th class="border-0">Type</th>
                                        <th class="border-0">Statut</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($recentTransactions as $transaction)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-initial rounded-circle bg-primary-subtle text-primary me-2">
                                                    {{ substr(optional($transaction->receveur)->prenom ?? 'I', 0, 1) }}
                                                </div>
                                                <div>
                                                    <h6 class="mb-0">
                                                        {{ optional($transaction->receveur)->nom ?? 'Inconnu' }}
                                                    </h6>
                                                    <small class="text-muted">
                                                        {{ optional($transaction->receveur)->prenom ?? 'Inconnu' }}
                                                    </small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="{{ $transaction->type === 'depot' ? 'text-success' : 'text-danger' }}">
                                                {{ $transaction->type === 'depot' ? '+' : '-' }}
                                                {{ number_format($transaction->montant, 0, ',', ' ') }} FCFA
                                            </span>
                                        </td>
                                        <td>
                                            <div>{{ $transaction->created_at->format('d/m/Y') }}</div>
                                            <small class="text-muted">{{ $transaction->created_at->format('H:i') }}</small>
                                        </td>
                                        <td>
                                            <span class="badge {{ $transaction->type === 'depot' ? 'bg-success-subtle text-success' : 'bg-danger-subtle text-danger' }}">
                                                {{ ucfirst($transaction->type) }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge bg-success">Complété</span>
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

        <!-- Graphique d'activité -->
        <div class="col-12 col-lg-5">
            <div class="card border-0 shadow-lg">
                <div class="card-header bg-white border-0 py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6 class="mb-0 text-primary">
                            <i class="fas fa-chart-bar me-2"></i>Activités Mensuelles
                        </h6>
                        <select class="form-select form-select-sm w-auto">
                            <option>6 derniers mois</option>
                            <option>Cette année</option>
                        </select>
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

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Gestion de la visibilité du solde
    const soldeElement = document.getElementById('solde');
    const toggleButton = document.getElementById('toggleSolde');
    let soldeVisible = true;

    toggleButton.addEventListener('click', function() {
        soldeVisible = !soldeVisible;
        soldeElement.style.visibility = soldeVisible ? 'visible' : 'hidden';
        toggleButton.querySelector('i').className = soldeVisible ? 'fas fa-eye' : 'fas fa-eye-slash';
    });

    // Configuration du graphique
    const ctx = document.getElementById('monthlyActivitiesChart').getContext('2d');
    const chart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: Object.keys(@json($dashboardData['deposit'])),
            datasets: [{
                label: 'Dépôts',
                data: Object.values(@json($dashboardData['deposit'])),
                backgroundColor: 'rgba(54, 162, 235, 0.7)',
                borderColor: 'rgb(54, 162, 235)',
                borderWidth: 1
            }, {
                label: 'Retraits',
                data: Object.values(@json($dashboardData['withdraw'])),
                backgroundColor: 'rgba(255, 99, 132, 0.7)',
                borderColor: 'rgb(255, 99, 132)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'top',
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            let label = context.dataset.label || '';
                            if (label) {
                                label += ': ';
                            }
                            label += new Intl.NumberFormat('fr-FR').format(context.raw) + ' FCFA';
                            return label;
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return new Intl.NumberFormat('fr-FR').format(value) + ' FCFA';
                        }
                    }
                }
            }
        }
    });
});
</script>

<style>
/* Styles personnalisés */
.bg-gradient-primary {
    background: linear-gradient(45deg, #4e73df, #224abe);
}

.avatar-initial {
    width: 36px;
    height: 36px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 500;
}

.progress {
    background-color: #edf2f9;
}

.table > :not(caption) > * > * {
    padding: 1rem;
}
</style>
@endsection