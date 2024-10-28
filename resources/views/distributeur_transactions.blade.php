@extends('layouts.sidebar-navbarD')
@section('content')
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Interface Bancaire</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
       .modal-lg { 
    max-width: 800px;
}

.company-header {
    text-align: center;
    border-bottom: 2px solid #f0f0f0;
    padding-bottom: 1rem;
}

.company-name {
    color: #2c3e50;
    font-weight: bold;
    margin-bottom: 0.5rem;
}

.company-details {
    color: #666;
    margin-bottom: 0;
}

.info-group {
    background-color: #f8f9fa;
    padding: 1rem;
    border-radius: 8px;
    height: 100%;
}

.info-title {
    color: #2c3e50;
    font-weight: bold;
    margin-bottom: 1rem;
    padding-bottom: 0.5rem;
    border-bottom: 2px solid #e9ecef;
}

.info-content p {
    margin-bottom: 0.5rem;
}

.transaction-details {
    background-color: #fff;
    border-radius: 8px;
    box-shadow: 0 0 15px rgba(0,0,0,0.05);
}

.table th {
    background-color: #f8f9fa;
    color: #2c3e50;
    font-weight: 600;
}

.footer-note {
    border-top: 1px solid #f0f0f0;
    padding-top: 1rem;
}

@media print {
    .modal-footer {
        display: none;
    }
    .btn-close {
        display: none;
    }
    .modal {
        position: absolute;
        left: 0;
        top: 0;
        margin: 0;
        padding: 0;
        overflow: visible!important;
    }
}

/* Styles du second code */
.card {
    border-radius: 15px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.action-button {
    border-radius: 30px;
    padding: 1.5rem;
    transition: transform 0.2s;
}

.action-button:hover {
    transform: translateY(-2px);
}

.balance-card {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
}

.transaction-row:hover {
    background-color: #f8f9fa;
}

.card-number {
    letter-spacing: 2px;
}

.pagination .page-link {
    border-radius: 50%;
    margin: 0 3px;
    width: 35px;
    height: 35px;
    display: flex;
    align-items: center;
    justify-content: center;
}

    </style>
</head>
<body class="bg-light">
    <div class="container-fluid py-4">
        <div class="row">
            <!-- Carte bancaire -->
            <div class="col-md-4">
                <h2 class="mb-4">Ma carte</h2>
                <div class="card balance-card mb-4">
                    <div class="card-body">
                        <h6 class="text-muted">Solde</h6>
                        <h3 class="mb-4">{{ $compte ? number_format($compte->solde, 2) : '0.00' }} FCFA</h3>
                        
                        <h6 class="text-muted">Nom d'utilisateur</h6>
                        <h5 class="mb-4">{{ $user->prenom }} {{ $user->nom }}</h5>
                        
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="card-number mb-0">{{ $user->telephone }}</h5>
                            <img src="{{ asset('images/num.png') }}" alt="Icon" width="50">
                        </div>
                    </div>
                </div>

                <!-- Boutons d'action -->
                <div class="d-grid gap-3">
                    <button class="btn btn-primary action-button d-flex align-items-center justify-content-center" 
                            data-bs-toggle="modal" data-bs-target="#depositModal">
                        <i class="fas fa-arrow-down me-2"></i>
                        Dépôt
                    </button>
                    
                    <button class="btn btn-success action-button d-flex align-items-center justify-content-center" 
                            data-bs-toggle="modal" data-bs-target="#withdrawalModal">
                        <i class="fas fa-arrow-up me-2"></i>
                        Retrait
                    </button>
                </div>
            </div>

            <!-- Liste des transactions -->
            <div class="col-md-8">
                <h2 class="mb-4">Liste des Transactions</h2>
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Nom</th>
                                        <th>Prénom</th>
                                        <th>ID Transaction</th>
                                        <th>Téléphone</th>
                                        <th>Date</th>
                                        <th>Montant</th>
                                        <th>Facture</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($transactions as $transaction)
                                    <tr class="transaction-row">
                                        <td>{{ $transaction->receveur->nom }}</td>
                                        <td>{{ $transaction->receveur->prenom }}</td>
                                        <td>{{ $transaction->id }}</td>
                                        <td>{{ $transaction->receveur->telephone }}</td>
                                        <td>{{ $transaction->created_at->format('Y-m-d') }}</td>
                                        <td class="{{ $transaction->type == 'depot' ? 'text-danger' : 'text-success' }}">
                                            {{ $transaction->type == 'depot' ? '-' : '+' }}{{ number_format(abs($transaction->montant), 2) }} FCFA
                                        </td>
                                        <td>
                                            <button class="btn btn-link text-primary" data-bs-toggle="modal" 
                                                    data-bs-target="#factureModal" 
                                                    data-id="{{ $transaction->id }}"
                                                    data-nom="{{ $transaction->receveur->nom }}"
                                                    data-prenom="{{ $transaction->receveur->prenom }}"
                                                    data-telephone="{{ $transaction->receveur->telephone }}"
                                                    data-date="{{ $transaction->created_at->format('Y-m-d') }}"
                                                    data-montant="{{ number_format($transaction->montant, 2) }} FCFA"
                                                    data-type="{{ $transaction->type }}">
                                                <i class="fas fa-file-invoice me-1"></i>
                                                Voir Facture
                                            </button>
                                        </td>
                                        <td>
                                            <button class="btn btn-danger btn-sm rounded-pill" 
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#annulationModal"
                                                    data-transaction-id="{{ $transaction->id }}">
                                                <i class="fas fa-times me-1"></i>
                                                Annulation
                                            </button>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- Pagination -->
                        <nav aria-label="Page navigation" class="mt-4">
                            <ul class="pagination justify-content-center">
                                <li class="page-item">
                                    <a class="page-link" href="#" aria-label="Previous">
                                        <i class="fas fa-chevron-left"></i>
                                    </a>
                                </li>
                                <li class="page-item active"><a class="page-link" href="#">1</a></li>
                                <li class="page-item"><a class="page-link" href="#">2</a></li>
                                <li class="page-item"><a class="page-link" href="#">3</a></li>
                                <li class="page-item"><a class="page-link" href="#">4</a></li>
                                <li class="page-item">
                                    <a class="page-link" href="#" aria-label="Next">
                                        <i class="fas fa-chevron-right"></i>
                                    </a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Les modals restent les mêmes mais avec des styles Bootstrap améliorés -->
              
        <!-- Modal pour le dépôt -->
<div class="modal fade" id="depositModal" tabindex="-1" aria-labelledby="depositModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered"> <!-- Taille du modal -->
        <div class="modal-content">
            <div class="modal-header text-center">
                <h5 class="modal-title w-100" id="depositModalLabel">Menu de dépôt</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body d-flex flex-column align-items-center"> <!-- Alignement centré -->
                <!-- Image ajoutée ici -->
                <img src="{{ asset('images/Minibank.png') }}" alt="Logo" class="img-fluid mx-auto d-block" width="300" style="margin-bottom: 20px;">
                
                <!-- Formulaire de dépôt -->
                <form id="depositForm" class="flex-grow-1 w-100 text-center" method="POST" action="{{ route('deposer') }}">
                    @csrf
                    <div class="mb-3 d-flex flex-column align-items-center">
                        <label for="depositAccount" class="form-label">Numéro de compte</label>
                        <input type="text" class="form-control mx-auto" id="depositAccount" name="account_number" placeholder="Saisir un numéro de compte" style="width: 70%;" required>
                    </div>
                    <div class="mb-3 d-flex flex-column align-items-center">
                        <label for="depositAmount" class="form-label">Montant à déposer</label>
                        <input type="number" class="form-control mx-auto" id="depositAmount" name="amount" placeholder="Montant à déposer" style="width: 70%;" required>
                        <div id="errorMessage" class="text-danger" style="display: none; margin-top: 10px;">
                            *Le montant à déposer doit être supérieur ou égal à 1000.
                        </div>

                        <div id="balanceErrorMessage" class="text-danger" style="display: none; margin-top: 10px;">
                            *Le montant à déposer ne peut pas dépasser votre solde actuel de {{ number_format($compte->solde, 2) }} FCFA.
                        </div>
                    </div>
                    <div class="text-center" style="color: blue; margin-bottom: 20px;">
                        Bonus = 1%
                    </div>
                    <div class="d-flex justify-content-center">
                        <button type="submit" class="btn btn-primary">Déposer</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


      <!-- Modal pour le retrait -->
      <div class="modal fade" id="withdrawalModal" tabindex="-1" aria-labelledby="withdrawalModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered"> <!-- Taille du modal -->
            <div class="modal-content">
                <div class="modal-header text-center">
                    <h5 class="modal-title w-100" id="withdrawalModalLabel">Menu de retrait</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body d-flex flex-column align-items-center"> <!-- Alignement centré -->
                    <!-- Image ajoutée ici -->
                    <img src="{{ asset('images/Minibank.png') }}" alt="Logo" class="img-fluid mx-auto d-block" width="300" style="margin-bottom: 20px;">
    
                    <!-- Formulaire de retrait -->
                    <form id="withdrawalForm" class="flex-grow-1 w-100 text-center" method="POST" action="{{ route('retirer') }}">
                        @csrf
                        <div class="mb-3 d-flex flex-column align-items-center">
                            <label for="withdrawalAccount" class="form-label">Numéro de compte</label>
                            <input type="text" class="form-control mx-auto" id="withdrawalAccount" name="account_number" placeholder="Saisir un numéro de compte" style="width: 70%;" required>
                        </div>
                        <div class="mb-3 d-flex flex-column align-items-center">
                            <label for="withdrawalAmount" class="form-label">Montant à retirer</label>
                            <input type="number" class="form-control mx-auto" id="withdrawalAmount" name="amount" placeholder="Montant à retirer" style="width: 70%;" required>
                            <div id="withdrawalErrorMessage" class="text-danger" style="display: none; margin-top: 10px;">
                                *Le montant à retirer doit être supérieur ou égal à 1000.
                            </div>
                            <div id="withdrawalBalanceErrorMessage" class="text-danger" style="display: none; margin-top: 10px;">
                                *Le montant à retirer ne peut pas dépasser votre solde actuel.
                            </div>
                        </div>
    
                        <div class="text-center" style="color: blue; margin-bottom: 20px;">
                            Bonus = 1%
                        </div>
                        
                        <div class="d-flex justify-content-center">
                            <button type="submit" class="btn btn-primary">Retirer</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>


    
  <!-- Modal Facture -->
<div class="modal fade" id="factureModal" tabindex="-1" aria-labelledby="factureModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="factureModalLabel">
                    <i class="fas fa-file-invoice"></i> Reçu de Transaction
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div class="company-header mb-4">
                    <h4 class="company-name">MiniBank</h4>
                    
                </div>
                
                <div class="receipt-details">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="info-group">
                                <h6 class="info-title">Informations Client</h6>
                                <div class="info-content">
                                    <p><span id="modalNomClient"></span> <span id="modalPrenomClient"></span></p>
                                    <p><i class="fas fa-phone"></i> <span id="modalTelephoneClient"></span></p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-group">
                                <h6 class="info-title">Détails Transaction</h6>
                                <div class="info-content">
                                    <p><i class="fas fa-hashtag"></i> ID: <span id="modalTransactionId"></span></p>
                                    <p><i class="far fa-calendar-alt"></i> Date: <span id="modalDate"></span></p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="transaction-details mt-4">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead class="table-light">
                                    <tr>
                                        <th>Type de Transaction</th>
                                        <th>Montant</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><span id="modalType"></span></td>
                                        <td class="text-end"><strong><span id="modalMontant"></span></strong></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="footer-note mt-4">
                        <p class="text-muted"><small>Ce reçu fait office de preuve de transaction. Veuillez le conserver pour vos archives.</small></p>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times"></i> Fermer
                </button>
                <button type="button" class="btn btn-primary" onclick="window.print()">
                    <i class="fas fa-print"></i> Imprimer
                </button>
            </div>
        </div>
    </div>
</div>





          <!-- Modal d'Annulation -->
        <div class="modal fade" id="annulationModal" tabindex="-1" aria-labelledby="annulationModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="annulationModalLabel">Annulation de la Transaction</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                    <form id="annulationForm" method="POST" action="{{ route('annuler.transaction') }}" >
                        @csrf
                        <div class="mb-3">
                            <label for="numeroCompte" class="form-label">Numéro de Compte</label>
                            <input type="text" class="form-control" name="numero_compte" id="numeroCompte" required>
                        </div>
                        <div class="mb-3">
                            <label for="transactionId" class="form-label">ID de Transaction</label>
                            <input type="text" class="form-control" name="transaction_id" id="transactionId" required>
                        </div>
                        <button type="submit" class="btn btn-danger">Annuler la Transaction</button>
                    </form>
                    </div>
                </div>
            </div>
        </div>



 <!-- Modal pour afficher les informations du client -->
 <div class="modal fade" id="clientModal" tabindex="-1" aria-labelledby="clientModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="clientModalLabel">Informations du Client</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="clientDetails">
                <p>Chargement des détails...</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
            </div>
        </div>
    </div>
</div>






    <script>






document.getElementById('depositForm').addEventListener('submit', function(event) {
                const accountNumber = document.getElementById('depositAccount').value.trim();
                const amount = parseFloat(document.getElementById('depositAmount').value);
                const errorMessage = document.getElementById('errorMessage');
                const balanceErrorMessage = document.getElementById('balanceErrorMessage');
                const currentBalance = {{ $compte ? $compte->solde : 0 }}; // Récupérez le solde actuel

                // Réinitialiser les messages d'erreur
                errorMessage.style.display = 'none';
                balanceErrorMessage.style.display = 'none';

                // Vérifier si le montant est inférieur à 1000
                if (amount < 1000) {
                    event.preventDefault(); // Empêche la soumission du formulaire
                    errorMessage.style.display = 'block'; // Affiche le message d'erreur
                } 
                // Vérifier si le montant dépasse le solde actuel
                else if (amount > currentBalance) {
                    event.preventDefault(); // Empêche la soumission du formulaire
                    balanceErrorMessage.style.display = 'block'; // Affiche le message d'erreur
                }
            });

            // Masquer le message d'erreur lorsque l'utilisateur saisit un nouvel input
            document.getElementById('depositAccount').addEventListener('input', function() {
                document.getElementById('errorMessage').style.display = 'none';
                document.getElementById('balanceErrorMessage').style.display = 'none';
            });

            document.getElementById('depositAmount').addEventListener('input', function() {
                const amount = parseFloat(this.value);
                const errorMessage = document.getElementById('errorMessage');
                const balanceErrorMessage = document.getElementById('balanceErrorMessage');

                // Masquer le message si le montant est valide
                if (amount >= 1000) {
                    errorMessage.style.display = 'none';
                } else {
                    errorMessage.style.display = 'block'; // Montant invalide
                }

                // Vérifier si le montant dépasse le solde actuel
                const currentBalance = {{ $compte ? $compte->solde : 0 }};
                if (amount <= currentBalance) {
                    balanceErrorMessage.style.display = 'none';
                } else {
                    balanceErrorMessage.style.display = 'block'; // Montant invalide
                }
            });



document.getElementById('withdrawalAccount').addEventListener('input', function() {
                const accountNumber = this.value;

                if (accountNumber.length >= 5) { // Vérifiez si le numéro de compte est valide
                    fetch(`/api/get-account-info/${accountNumber}`)
                        .then(response => response.json())
                        .then(data => {
                            if (!data.success) {
                                alert(data.message); // Affichez le message d'erreur
                            }
                        })
                        .catch(error => {
                            console.error('Erreur lors de la récupération des informations :', error);
                        });
                }
            });

            document.getElementById('withdrawalForm').addEventListener('submit', function(event) {
                const amount = parseFloat(document.getElementById('withdrawalAmount').value);
                const withdrawalErrorMessage = document.getElementById('withdrawalErrorMessage');
                const withdrawalBalanceErrorMessage = document.getElementById('withdrawalBalanceErrorMessage');

                // Réinitialiser les messages d'erreur
                withdrawalErrorMessage.style.display = 'none';
                withdrawalBalanceErrorMessage.style.display = 'none';

                // Debugging: afficher les valeurs
                console.log('Montant à retirer:', amount);

                // Vérifier si le montant est inférieur à 1000
                if (amount < 1000) {
                    event.preventDefault(); // Empêche la soumission du formulaire
                    withdrawalErrorMessage.style.display = 'block'; // Affiche le message d'erreur
                } 
                // Vérifier si le montant dépasse le solde du client (si nécessaire, vous pouvez gérer cela ici)
                // Vous aurez besoin d'ajouter une logique pour obtenir le solde ici si vous le souhaitez
            });

            // Masquer les messages d'erreur lors de la saisie
            document.getElementById('withdrawalAmount').addEventListener('input', function() {
                const amount = parseFloat(this.value);
                const withdrawalErrorMessage = document.getElementById('withdrawalErrorMessage');
                const withdrawalBalanceErrorMessage = document.getElementById('withdrawalBalanceErrorMessage');

                // Vérifier si le montant est valide
                withdrawalErrorMessage.style.display = (amount >= 1000) ? 'none' : 'block';
                // Vous pouvez également gérer la vérification du solde ici si nécessaire
            });




$('#annulationModal').on('show.bs.modal', function(event) {
                var button = $(event.relatedTarget); // Bouton qui a déclenché le modal
                var transactionId = button.data('transaction-id'); // Récupérer l'ID de la transaction

                // Mettre à jour le champ de l'ID de la transaction
                var modal = $(this);
                modal.find('#transactionId').val(transactionId);
            });
</script>

<script>
        document.querySelectorAll('button[data-bs-target="#factureModal"]').forEach(button => {
    button.addEventListener('click', function() {
        // Récupérer les données des attributs data-*
        const transactionId = this.getAttribute('data-id');
        const nomClient = this.getAttribute('data-nom');
        const prenomClient = this.getAttribute('data-prenom');
        const telephoneClient = this.getAttribute('data-telephone');
        const date = this.getAttribute('data-date');
        const montant = this.getAttribute('data-montant');
        const type = this.getAttribute('data-type');

        // Afficher les données dans la console
        console.log('Transaction ID:', transactionId);
        console.log('Nom Client:', nomClient);
        console.log('Prénom Client:', prenomClient);
        console.log('Téléphone Client:', telephoneClient);
        console.log('Date:', date);
        console.log('Montant:', montant);
        console.log('Type:', type);

        // Remplir le modal avec les données
        document.getElementById('modalTransactionId').textContent = transactionId;
        document.getElementById('modalNomClient').textContent = nomClient;
        document.getElementById('modalPrenomClient').textContent = prenomClient;
        document.getElementById('modalTelephoneClient').textContent = telephoneClient;
        document.getElementById('modalDate').textContent = date;
        document.getElementById('modalMontant').textContent = montant;
        document.getElementById('modalType').textContent = type;
    });
});
;




</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Les scripts JavaScript existants restent les mêmes -->
</body>
</html>
@endsection