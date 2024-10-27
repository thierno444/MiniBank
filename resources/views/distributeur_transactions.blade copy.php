<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Système de Transfert d'Argent</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <style>
                .transactions {
            width: 100%;
            max-width: 1500px;
            height: 350px;
            background-color: #f8f9fa;
            border: 1px solid #ced4da;
            border-radius: 20px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            margin-left: auto;
            margin-right: auto;
            margin-top: 5px;
            padding: 20px;
            overflow-y: auto;
        }

        .transaction-title {
            margin: 20px 0;
            text-align: center;
        }

        .table th {
            background-color: #f1f1f1;
            position: sticky;
            top: 0;
        }

        .pagination {
            margin-top: 20px;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .pagination a {
            color: #2D60FF;
            text-decoration: none;
            padding: 5px 10px;
        }

        .pagination a:hover {
            background-color: #f1f1f1;
            border-radius: 5px;
        }

        @media (max-width: 768px) {
            .transactions {
                margin: 5px;
                padding: 10px;
            }
            
            .table {
                font-size: 14px;
            }
        }
        :root {
            --primary-color: #2D60FF;
            --secondary-color: #41D4A8;
            --danger-color: #D61E33;
            --dark-blue: #1A237E;
        }

        body {
            background: #f8f9ff;
            font-family: 'Segoe UI', sans-serif;
        }

        .card-container {
            perspective: 1000px;
            margin-top: 30px;
        }

        .banking-card {
            background: linear-gradient(135deg, var(--dark-blue), var(--primary-color));
            border-radius: 20px;
            padding: 25px;
            color: white;
            transform-style: preserve-3d;
            transition: transform 0.5s;
            box-shadow: 0 10px 20px rgba(0,0,0,0.2);
        }

        .banking-card:hover {
            transform: rotateY(5deg);
        }

        .card-chip {
            width: 50px;
            height: 40px;
            background: linear-gradient(135deg, #FFD700, #FFA500);
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .action-button {
            border-radius: 30px;
            padding: 20px;
            transition: transform 0.3s, box-shadow 0.3s;
            cursor: pointer;
        }

        .action-button:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }

        .modal-content {
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.3);
        }

        .form-control {
            border-radius: 10px;
            padding: 12px;
            border: 2px solid #e0e0e0;
        }

        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(45, 96, 255, 0.25);
        }

        .balance-amount {
            font-size: 2.5rem;
            font-weight: bold;
            margin: 15px 0;
        }

        .card-number {
            letter-spacing: 2px;
            font-size: 1.2rem;
            margin: 15px 0;
        }

        .toast-notification {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 1050;
            display: none;
        }
    </style>
</head>
<body>
    <!-- Toast Notification -->
    <div class="toast-notification">
        <div class="toast" role="alert">
            <div class="toast-header">
                <strong class="me-auto">Notification</strong>
                <button type="button" class="btn-close" data-bs-dismiss="toast"></button>
            </div>
            <div class="toast-body"></div>
        </div>
    </div>

    <div class="container">
        <h2 class="mt-4 mb-4">Mon Espace Bancaire</h2>
        
        <!-- Carte Bancaire -->
        <div class="card-container">
            <div class="banking-card">
                <div class="card-chip"></div>
                <div class="card-details">
                    <div class="balance-label">Solde disponible</div>
                    <div class="balance-amount">
                        {{ number_format($compte->solde, 2) }} FCFA
                    </div>
                    <div class="card-number">
                        **** **** **** {{ substr($user->account_number, -4) }}
                    </div>
                    <div class="card-holder">
                        {{ $user->prenom }} {{ $user->nom }}
                    </div>
                    <div class="card-info d-flex justify-content-between align-items-center mt-3">
                        <span>{{ $user->telephone }}</span>
                        <i class="fas fa-wifi fa-rotate-90"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Boutons d'action -->
        <div class="row mt-5">
            <div class="col-md-4">
                <div class="action-button bg-primary text-white" data-bs-toggle="modal" data-bs-target="#depositModal">
                    <i class="fas fa-money-bill-wave fa-2x mb-2"></i>
                    <h5>Dépôt</h5>
                </div>
            </div>
            <div class="col-md-4">
                <div class="action-button bg-success text-white" data-bs-toggle="modal" data-bs-target="#withdrawalModal">
                    <i class="fas fa-hand-holding-usd fa-2x mb-2"></i>
                    <h5>Retrait</h5>
                </div>
            </div>
            <div class="col-md-4">
                <div class="action-button bg-danger text-white">
                    <i class="fas fa-times-circle fa-2x mb-2"></i>
                    <h5>Annulation</h5>
                </div>
            </div>
        </div>

        <!-- Modal Dépôt -->
        [Votre modal de dépôt existant avec le style mis à jour]

        <!-- Modal Retrait -->
        [Votre modal de retrait existant avec le style mis à jour]
    </div>

    <h3 class="transaction-title">Liste des Transactions</h3>

    <div class="transactions">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Nom</th>
                    <th>Prénom</th>
                    <th>ID Transaction</th>
                    <th>Téléphone</th>
                    <th>Date</th>
                    <th>Montant</th>
                    <th>Facture</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($transactions as $transaction)
                    <tr>
                        <td>{{ $transaction->receveur->nom }}</td>
                        <td>{{ $transaction->receveur->prenom }}</td>
                        <td>{{ $transaction->id }}</td>
                        <td>{{ $transaction->receveur->telephone }}</td>
                        <td>{{ $transaction->created_at->format('Y-m-d') }}</td>
                        <td style="color: {{ $transaction->type == 'depot' ? 'red' : 'green' }};">
                            {{ $transaction->type == 'depot' ? '-' : '+' }}{{ number_format(abs($transaction->mountant), 2) }} FCFA
                        </td>
                        <td>
                            <button type="button" class="btn btn-link" data-bs-toggle="modal" data-bs-target="#factureModal" 
                                data-id="{{ $transaction->id }}"
                                data-nom="{{ $transaction->receveur->nom }}"
                                data-prenom="{{ $transaction->receveur->prenom }}"
                                data-telephone="{{ $transaction->receveur->telephone }}"
                                data-date="{{ $transaction->created_at->format('Y-m-d') }}"
                                data-montant="{{ number_format($transaction->mountant, 2) }} FCFA"
                                data-type="{{ $transaction->type }}">
                                Voir Facture
                            </button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Les modals et le reste du code restent identiques -->
    <!-- ... -->

    <nav aria-label="Navigation des pages" class="pagination">
        <a href="#" class="prev"><i class="bi bi-arrow-left"></i> Précédent</a>
        <div class="pages">
            <a href="#">1</a>
            <a href="#">2</a>
            <a href="#">3</a>
            <a href="#">4</a>
        </div>
        <a href="#" class="next">Suivant <i class="bi bi-arrow-right"></i></a>
    </nav>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Fonction pour afficher les notifications
        function showNotification(message, type = 'success') {
            const toast = document.querySelector('.toast');
            const toastBody = toast.querySelector('.toast-body');
            
            toast.classList.remove('bg-success', 'bg-danger');
            toast.classList.add(`bg-${type}`);
            toastBody.textContent = message;
            
            const bsToast = new bootstrap.Toast(toast);
            bsToast.show();
        }

        // Validation des formulaires avec feedback en temps réel
        function validateAmount(input, minAmount, currentBalance) {
            const amount = parseFloat(input.value);
            const isValid = amount >= minAmount && amount <= currentBalance;
            
            input.classList.toggle('is-invalid', !isValid);
            input.classList.toggle('is-valid', isValid);
            
            return isValid;
        }

        // Écouteurs d'événements pour les formulaires
        document.querySelectorAll('form').forEach(form => {
            form.addEventListener('submit', (e) => {
                e.preventDefault();
                
                // Simulation de traitement
                setTimeout(() => {
                    showNotification('Transaction effectuée avec succès!');
                    const modal = bootstrap.Modal.getInstance(form.closest('.modal'));
                    modal.hide();
                    form.reset();
                }, 1000);
            });
        });

        // Animation de la carte
        const card = document.querySelector('.banking-card');
        card.addEventListener('mousemove', (e) => {
            const rect = card.getBoundingClientRect();
            const x = e.clientX - rect.left;
            const y = e.clientY - rect.top;
            
            const centerX = rect.width / 2;
            const centerY = rect.height / 2;
            
            const rotateX = (y - centerY) / 10;
            const rotateY = -(x - centerX) / 10;
            
            card.style.transform = `perspective(1000px) rotateX(${rotateX}deg) rotateY(${rotateY}deg)`;
        });

        card.addEventListener('mouseleave', () => {
            card.style.transform = 'perspective(1000px) rotateX(0) rotateY(0)';
        });
    </script>
</body>
</html>




    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>