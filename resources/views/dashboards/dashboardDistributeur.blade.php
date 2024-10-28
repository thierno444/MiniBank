@extends('layouts.sidebar-navbarD')

@section('content')
<div class="container-fluid py-4">
    <div class="row g-4 mb-4">
        <!-- Carte du solde et QR Code -->
        <div class="col-12 col-md-6">
            <div class="card border-0 shadow-lg h-100">
                <div class="card-body position-relative bg-gradient-primary text-white rounded">
                    <div class="row">
                        <div class="col-md-7">
                            <h6 class="text-white-50 mb-3">
                                <i class="fas fa-wallet me-2"></i>Solde disponible
                            </h6>
                            <div class="d-flex align-items-center mb-3">
                                <h3 class="display-6 mb-0 me-3" id="solde">
                                    {{ number_format($solde, 0, ',', ' ') }} FCFA
                                </h3>
                                <button class="btn btn-light btn-sm rounded-circle" id="toggleSolde">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                            <div class="d-flex align-items-center">
                                <i class="fas fa-hashtag me-2"></i>
                                <span>{{ $numCompte }}</span>
                            </div>
                        </div>
                        
                    </div>
                </div>
            </div>
        </div>

        <!-- Carte des plafonds -->
        <div class="col-12 col-md-6">
            <div class="card border-0 shadow-lg h-100">
                <div class="card-header bg-white border-0 py-3">
                    <h6 class="mb-0 text-primary">
                        <i class="fas fa-chart-line me-2"></i>Plafonds du Compte
                    </h6>
                </div>
                <div class="card-body">
                    <div class="mb-4">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="text-muted">Solde Maximum</span>
                            <strong>{{ number_format($plafondsCompte['solde_maximum'], 0, ',', ' ') }} FCFA</strong>
                        </div>
                        <div class="progress" style="height: 8px;">
                            @php
                                $soldePercentage = ($solde / $plafondsCompte['solde_maximum']) * 100;
                            @endphp
                            <div class="progress-bar bg-success" role="progressbar" style="width: {{ $soldePercentage }}%"></div>
                        </div>
                        <small class="text-muted">{{ number_format($soldePercentage, 1) }}% utilisé</small>
                    </div>

                    <div class="mb-4">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="text-muted">Cumul Mensuel</span>
                            <strong>{{ number_format($plafondsCompte['cumul_mensuel_maximum'], 0, ',', ' ') }} FCFA</strong>
                        </div>
                        <div class="progress" style="height: 8px;">
                            @php
                                $cumulPercentage = (($plafondsCompte['cumul_mensuel_maximum'] - $plafondsCompte['cumul_maximum_restant']) / $plafondsCompte['cumul_mensuel_maximum']) * 100;
                            @endphp
                            <div class="progress-bar bg-warning" role="progressbar" style="width: {{ $cumulPercentage }}%"></div>
                        </div>
                        <small class="text-muted">{{ number_format($cumulPercentage, 1) }}% utilisé</small>
                    </div>

                    <div class="text-center mt-4">
                        <div class="bg-success-subtle rounded-3 p-3">
                            <h6 class="text-success mb-1">Cumul Maximum Restant</h6>
                            <h4 class="mb-0">{{ number_format($plafondsCompte['cumul_maximum_restant'], 0, ',', ' ') }} FCFA</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Transactions récentes -->
    <div class="card border-0 shadow-lg mb-4">
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
                    <i class="fas fa-receipt fa-3x text-muted mb-3"></i>
                    <p class="text-muted mb-0">Aucune transaction récente à afficher</p>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="border-0">Bénéficiaire</th>
                                <th class="border-0">Montant</th>
                                <th class="border-0">Date & Heure</th>
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
                                            <h6 class="mb-0">{{ optional($transaction->receveur)->nom ?? 'Inconnu' }}</h6>
                                            <small class="text-muted">{{ optional($transaction->receveur)->prenom ?? 'Inconnu' }}</small>
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
                                    <small class="text-muted">{{ $transaction->created_at->format('H:i:s') }}</small>
                                </td>
                                <td>
                                    @php
                                        $typeClass = match($transaction->type) {
                                            'depot' => 'bg-success-subtle text-success',
                                            'retrait' => 'bg-danger-subtle text-danger',
                                            default => 'bg-primary-subtle text-primary'
                                        };
                                    @endphp
                                    <span class="badge {{ $typeClass }}">
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

    <!-- Graphique d'activités -->
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
            <canvas id="monthlyActivitiesChart" style="height: 300px;"></canvas>
        </div>
    </div>
</div>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Gestion de la visibilité du solde
    const soldeElement = document.getElementById('solde');
    const toggleButton = document.getElementById('toggleSolde');
    const qrCodeElement = document.getElementById('qrCode');
    
    toggleButton.addEventListener('click', function() {
        const isVisible = soldeElement.style.visibility === 'visible';
        soldeElement.style.visibility = isVisible ? 'hidden' : 'visible';
        toggleButton.innerHTML = isVisible ? '<i class="fas fa-eye-slash"></i>' : '<i class="fas fa-eye"></i>';
    });

    // Générer QR Code
    function generateQRCode() {
        // Logic for generating QR Code
    }

    generateQRCode();

    // Graphique des activités mensuelles
    const ctx = document.getElementById('monthlyActivitiesChart').getContext('2d');
    const monthlyActivitiesChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
            datasets: [{
                label: 'Dépôts',
                data: [50, 60, 70, 80, 90, 100],
                borderColor: 'rgba(75, 192, 192, 1)',
                fill: false,
            }, {
                label: 'Retraits',
                data: [30, 40, 50, 40, 60, 70],
                borderColor: 'rgba(255, 99, 132, 1)',
                fill: false,
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    display: true,
                    position: 'top',
                },
                tooltip: {
                    mode: 'index',
                    intersect: false,
                },
            },
            interaction: {
                mode: 'index',
                intersect: false,
            },
        }
    });
});
</script>
<style>
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
    
    /* Animation pour le QR Code */
    #qrCode {
        transition: filter 0.3s ease-in-out;
    }
    </style>
@endsection
