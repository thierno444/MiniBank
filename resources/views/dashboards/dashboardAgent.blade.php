@extends('layouts.sidebar-navbarA')

@section('content')
<div class="container-fluid py-4">
    <!-- Vue d'ensemble -->
    <div class="row g-4 mb-4">
        <!-- Carte des informations de l'agent -->
        <div class="col-12 col-md-6">
            <div class="card border-0 shadow-lg h-100">
                <div class="card-body position-relative bg-gradient-primary text-white rounded">
                    <div class="row">
                        <div class="col-md-7">
                            <h6 class="text-white-50 mb-3">
                                <i class="fas fa-user me-2"></i>Informations de l'Agent
                            </h6>
                            <div class="mb-3">
                                <p class="mb-2"><strong>Nom :</strong> {{ auth()->user()->nom }}</p>
                                <p class="mb-2"><strong>Prénom :</strong> {{ auth()->user()->prenom }}</p>
                                <p class="mb-2"><strong>Numéro de Compte :</strong> {{ auth()->user()->num_compte }}</p>
                                <p class="mb-2"><strong>Carte d'Identité :</strong> {{ auth()->user()->carte_identite }}</p>
                                <p class="mb-0"><strong>Téléphone :</strong> {{ auth()->user()->telephone }}</p>
                            </div>
                        </div>
                        <!-- QR Code -->
                       
                    </div>
                </div>
            </div>
        </div>

        <!-- Carte des transactions récentes -->
        <div class="col-12 col-md-6">
            <div class="card border-0 shadow-lg h-100">
                <div class="card-header bg-white border-0 py-3">
                    <h6 class="mb-0 text-primary">
                        <i class="fas fa-exchange-alt me-2"></i>Transactions Récentes
                    </h6>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="border-0">Distributeur</th>
                                    <th class="border-0">Montant</th>
                                    <th class="border-0">Date & Heure</th>
                                    <th class="border-0">Type</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($transactions as $transaction)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-initial rounded-circle bg-primary-subtle text-primary me-2">
                                                {{ substr($transaction->distributeur->prenom, 0, 1) }}
                                            </div>
                                            <div>
                                                <h6 class="mb-0">{{ $transaction->distributeur->nom }}</h6>
                                                <small class="text-muted">{{ $transaction->distributeur->prenom }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="{{ $transaction->type == 'depot' ? 'text-success' : 'text-danger' }}">
                                            {{ $transaction->type == 'depot' ? '+' : '-' }}{{ number_format(abs($transaction->montant), 0, ',', ' ') }} FCFA
                                        </span>
                                    </td>
                                    <td>
                                        <div>{{ $transaction->created_at->format('d/m/Y') }}</div>
                                        <small class="text-muted">{{ $transaction->created_at->format('H:i:s') }}</small>
                                    </td>
                                    <td>
                                        @php
                                            $typeClass = $transaction->type == 'depot' ? 'bg-success-subtle text-success' : 'bg-danger-subtle text-danger';
                                        @endphp
                                        <span class="badge {{ $typeClass }}">
                                            {{ ucfirst($transaction->type) }}
                                        </span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Graphiques -->
    <div class="row g-4">
        <div class="col-12">
            <div class="card border-0 shadow-lg">
                <div class="card-header bg-white border-0 py-3">
                    <h6 class="mb-0 text-primary">
                        <i class="fas fa-chart-bar me-2"></i>Statistiques des Transactions
                    </h6>
                </div>
                <div class="card-body">
                    <canvas id="transactionsChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Configuration du graphique
    const ctx = document.getElementById('transactionsChart').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['Dépôts', 'Retraits'],
            datasets: [{
                label: 'Montant',
                data: [{{ $totalDeposits }}, {{ $totalWithdrawals }}],
                backgroundColor: ['rgba(40, 167, 69, 0.7)', 'rgba(220, 53, 69, 0.7)'],
                borderColor: ['rgb(40, 167, 69)', 'rgb(220, 53, 69)'],
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
                            return context.dataset.label + ': ' + 
                                   new Intl.NumberFormat('fr-FR').format(context.raw) + ' FCFA';
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

#qrCode {
    transition: filter 0.3s ease-in-out;
}
</style>
@endsection