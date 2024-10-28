@extends('layouts.sidebar-navbarC')
@section('content')


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>MiniBank - Dashboard</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <!DOCTYPE html>
    <html lang="fr">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Mon Application</title>
        <div class="container-fluid py-4">
            <!-- Carte principale et bouton de transfert -->
            <div class="row g-4 mb-4">
                <div class="col-12 col-lg-8">
                    <div class="card border-0 shadow-lg h-100">
                        <div class="card-body position-relative bg-gradient-custom text-white rounded">
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="mb-4">
                                        <h6 class="text-white-50 mb-2">Solde disponible</h6>
                                        <div class="d-flex align-items-center">
                                            <h3 class="mb-0">{{ number_format($compte->solde, 0) }} FCFA</h3>
                                            <button class="btn btn-light btn-sm rounded-circle ms-2">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <h6 class="text-white-50 mb-2">Nom d'utilisateur</h6>
                                        <h5 class="mb-0">{{ $client->prenom }} {{ $client->nom }}</h5>
                                    </div>
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-phone me-2"></i>
                                        <span>{{ $client->telephone }}</span>
                                    </div>
                                </div>
                                <div class="col-md-4 text-center">
                                    <div class="bg-white p-3 rounded shadow-sm">
                                        <img src="{{ asset('images/num.png') }}" alt="QR Code" class="img-fluid" width="70">
                                        <div class="mt-2">
                                            <small class="text-primary">QR Code de compte</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-lg-4">
                    <div class="h-100 d-flex align-items-center">
                        <button onclick="openModal()" class="btn btn-primary btn-lg w-100 transfer-button">
                            <i class="fas fa-exchange-alt me-2"></i>
                            Transférer
                        </button>
                        
                    </div>
                </div>
            </div>
        
            <!-- Tableau des transactions -->
            <div class="card border-0 shadow-lg">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="mb-0">Liste des Transactions</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th>Émetteur</th>
                                    <th>Receveur</th>
                                    <th>ID Transaction</th>
                                    <th>Date</th>
                                    <th>Montant</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($transactions as $transaction)
                                <tr>
                                    <td>{{ $transaction->emetteur->nom }} {{ $transaction->emetteur->prenom }}</td>
                                    <td>{{ $transaction->receveur->nom }} {{ $transaction->receveur->prenom }}</td>
                                    <td>{{ $transaction->id }}</td>
                                    <td>{{ $transaction->created_at->format('d/m/Y H:i') }}</td>
                                    <td>
                                        <span class="{{ $transaction->type == 'depot' ? 'text-success' : 'text-danger' }}">
                                            {{ $transaction->type == 'depot' ? '+' : '-' }}{{ number_format($transaction->montant, 2) }} FCFA
                                        </span>
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-link text-primary" 
        data-bs-toggle="modal" 
        data-bs-target="#factureModalClient"
        data-id="{{ $transaction->id }}"
        data-nom-emetteur="{{ $transaction->emetteur->nom }}"
        data-prenom-emetteur="{{ $transaction->emetteur->prenom }}"
        data-nom-receveur="{{ $transaction->receveur->nom }}"
        data-prenom-receveur="{{ $transaction->receveur->prenom }}"
        data-telephone-receveur="{{ $transaction->receveur->telephone }}"
        data-date="{{ $transaction->created_at->format('Y-m-d') }}"
        data-montant="{{ number_format($transaction->montant, 2) }} FCFA"
        data-type="{{ $transaction->type }}">
    <i class="fas fa-file-invoice me-1"></i>
    Voir Facture
</button>

                                        
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        
        <style>
        .bg-gradient-custom {
            background: linear-gradient(45deg, #2D60FF, #1E4BD1);
        }
        
        .transfer-button {
            height: 120px;
            border-radius: 40px;
            font-size: 1.25rem;
            transition: transform 0.2s;
        }
        
        .transfer-button:hover {
            transform: translateY(-2px);
        }
        
        .card {
            border-radius: 20px;
            overflow: hidden;
        }
        
        .table th {
            font-weight: 600;
            background-color: #f8f9fa;
        }
        
        .table td {
            vertical-align: middle;
        }
        
        .btn-link {
            text-decoration: none;
        }
        
        .btn-link:hover {
            text-decoration: underline;
        }
        </style>

<!-- Modal de transfert-->
<div id="transferModal" class="modal" style="display:none; position: fixed; z-index: 1; left: 0; top: 0; width: 100%; height: 100%; overflow: auto; background-color: rgba(0, 0, 0, 0.5);">
    <div class="modal-content" style="padding: 40px; border-radius: 20px; background-color: white; width: 600px; margin: auto; position: relative; top: 50px;">
        <span class="close" onclick="closeModal()" style="position: absolute; top: 10px; right: 15px; cursor: pointer; font-size: 20px;">&times;</span>
        
        <img src="{{ asset('images/Minibank.png') }}" alt="Logo" style="display: block; width: 300px; margin: 0 auto 20px auto;">
        
        <h2 style="text-align: center;">Transférer de l'argent</h2>
        <form id="transferForm" action="{{ route('transferer') }}" method="POST">
        @csrf <!-- Ajoutez le jeton CSRF pour protéger contre les attaques CSRF -->
            <label for="numero_compte">Numéro de compte:</label>
            <input type="text" id="numero_compte" name="numero_compte" required style="width: 100%; padding: 10px; margin: 10px 0; border: 1px solid #ccc; border-radius: 5px;">
            
            <label for="montant_envoye">Montant envoyé:</label>
            <input type="number" id="montant_envoye" name="montant_envoye" required style="width: 100%; padding: 10px; margin: 20px 0 5px 0; border: 1px solid #ccc; border-radius: 5px;">
            <div id="errorMessage" class="text-danger" style="display: none; margin-top: 5px;">
                *Le montant envoyé doit être supérieur à 500.
            </div>
            <div id="balanceErrorMessage" class="text-danger" style="display: none; margin-top: 5px;">
                *Votre solde est insuffisant pour effectuer ce transfert.
            </div>

            <label for="montant_recu">Montant reçu:</label>
            <input type="number" id="montant_recu" name="montant_recu" required style="width: 100%; padding: 10px; margin: 20px 0 5px 0; border: 1px solid #ccc; border-radius: 5px;">
            <div id="receivedAmountError" class="text-danger" style="display: none; margin-top: 5px;">
                *Le montant reçu doit être supérieur à 500.
            </div>
            <div id="receivedBalanceErrorMessage" class="text-danger" style="display: none; margin-top: 5px;">
                *Le montant reçu doit être inférieur au solde disponible.
            </div>

            <button type="submit" style="width: 100%; background-color: #2D60FF; color: white; border: none; border-radius: 5px; padding: 10px;">Confirmer</button>
        </form>
    </div>
</div>



<!-- Modal Facture -->
<div class="modal fade" id="factureModalClient" tabindex="-1" aria-labelledby="factureModalClientLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="factureModalClientLabel">
                    <i class="bi bi-receipt"></i> Reçu de transaction
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <!-- En-tête de la facture -->
                <div class="text-center mb-4">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo" style="height: 60px;" class="mb-3">
                    <h4 class="text-primary">Reçu de Transaction</h4>
                    <div class="badge bg-success mb-2" id="modalStatut">Transaction réussie</div>
                </div>

                <!-- Informations de transaction -->
                <div class="border rounded-3 p-4 mb-4 bg-light">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <div class="text-muted small">ID Transaction</div>
                                <div class="h5" id="modalTransactionIdClient"></div>
                            </div>
                            <div class="mb-3">
                                <div class="text-muted small">Date et Heure</div>
                                <div id="modalDateClient"></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <div class="text-muted small">Type de transaction</div>
                                <div class="h5" id="modalTypeClient"></div>
                            </div>
                            <div class="mb-3">
                                <div class="text-muted small">Montant</div>
                                <div class="h3 text-primary" id="modalMontantClient"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Détails des parties -->
                <div class="row g-4">
                    <!-- Émetteur -->
                    <div class="col-md-6">
                        <div class="border rounded-3 p-3">
                            <h6 class="text-primary mb-3">
                                <i class="bi bi-person-fill"></i> Émetteur
                            </h6>
                            <div class="mb-2">
                                <div class="text-muted small">Nom complet</div>
                                <div class="fw-bold">
                                    <span id="modalPrenomEmetteur"></span>
                                    <span id="modalNomEmetteur"></span>
                                </div>
                            </div>
                            <div class="mb-2">
                                <div class="text-muted small">Numéro de compte</div>
                                <div id="modalCompteEmetteur">••••••••••••</div>
                            </div>
                        </div>
                    </div>

                    <!-- Receveur -->
                    <div class="col-md-6">
                        <div class="border rounded-3 p-3">
                            <h6 class="text-primary mb-3">
                                <i class="bi bi-person-fill"></i> Bénéficiaire
                            </h6>
                            <div class="mb-2">
                                <div class="text-muted small">Nom complet</div>
                                <div class="fw-bold">
                                    <span id="modalPrenomReceveur"></span>
                                    <span id="modalNomReceveur"></span>
                                </div>
                            </div>
                            <div class="mb-2">
                                <div class="text-muted small">Téléphone</div>
                                <div id="modalTelephoneReceveur"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- QR Code -->
                <div class="text-center mt-4">
                    <div id="qrcode" class="d-inline-block border p-2"></div>
                    <div class="text-muted small mt-2">Scannez pour vérifier la transaction</div>
                </div>
            </div>

            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                    <i class="bi bi-x"></i> Fermer
                </button>
                <button type="button" class="btn btn-primary" onclick="imprimerFacture()">
                    <i class="bi bi-printer"></i> Imprimer
                </button>
                <button type="button" class="btn btn-success" onclick="telechargerPDF()">
                    <i class="bi bi-download"></i> Télécharger PDF
                </button>
            </div>
        </div>
    </div>
</div>

<style>
    #factureModalClient .modal-content {
        border: none;
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
    }

    #factureModalClient .badge {
        padding: 0.5rem 1rem;
        font-weight: 500;
    }

    #factureModalClient .border {
        border-color: #e9ecef !important;
    }

    #factureModalClient .text-primary {
        color: #2D60FF !important;
    }

    #factureModalClient .bg-primary {
        background-color: #2D60FF !important;
    }

    #factureModalClient .modal-header {
        border-bottom: none;
        padding: 1.5rem;
    }

    #factureModalClient .modal-footer {
        border-top: none;
        padding: 1.5rem;
    }

    @media print {
        .modal-footer {
            display: none !important;
        }
    }
</style>

<script>
// Script pour initialiser le QR code et gérer les actions
document.addEventListener('DOMContentLoaded', function() {
    // Gestionnaire d'événement pour l'ouverture de la modal
    document.getElementById('factureModalClient').addEventListener('show.bs.modal', function(event) {
        const button = event.relatedTarget;
        const transaction = JSON.parse(button.dataset.transaction);

        // Remplir les champs de la modal
        document.getElementById('modalTransactionIdClient').textContent = transaction.id;
        document.getElementById('modalNomEmetteur').textContent = transaction.emetteur.nom;
        document.getElementById('modalPrenomEmetteur').textContent = transaction.emetteur.prenom;
        document.getElementById('modalNomReceveur').textContent = transaction.receveur.nom;
        document.getElementById('modalPrenomReceveur').textContent = transaction.receveur.prenom;
        document.getElementById('modalTelephoneReceveur').textContent = transaction.receveur.telephone;
        document.getElementById('modalDateClient').textContent = new Date(transaction.created_at).toLocaleString();
        document.getElementById('modalMontantClient').textContent = new Intl.NumberFormat('fr-FR').format(transaction.montant) + ' FCFA';
        document.getElementById('modalTypeClient').textContent = transaction.type.charAt(0).toUpperCase() + transaction.type.slice(1);

        // Génération du QR Code (nécessite la bibliothèque qrcode.js)
        const qr = new QRCode(document.getElementById("qrcode"), {
            text: `ID:${transaction.id},Date:${transaction.created_at},Montant:${transaction.montant}`,
            width: 128,
            height: 128
        });
    });
});

// Fonction pour imprimer la facture
function imprimerFacture() {
    window.print();
}

// Fonction pour télécharger en PDF (nécessite une bibliothèque PDF)
function telechargerPDF() {
    // Implémentation du téléchargement PDF
    // Vous pouvez utiliser html2pdf.js ou une autre bibliothèque
    alert('Fonctionnalité en cours de développement');
}
</script>


<script>
    // Événement au clic sur le bouton "Voir Facture" pour le client
    document.querySelectorAll('button[data-bs-target="#factureModalClient"]').forEach(button => {
        button.addEventListener('click', function() {
            // Récupérer les données des attributs data-*
            const transactionId = this.getAttribute('data-id');
            const nomEmetteur = this.getAttribute('data-nom-emetteur');
            const prenomEmetteur = this.getAttribute('data-prenom-emetteur');
            const nomReceveur = this.getAttribute('data-nom-receveur');
            const prenomReceveur = this.getAttribute('data-prenom-receveur');
            const telephoneReceveur = this.getAttribute('data-telephone-receveur');
            const date = this.getAttribute('data-date');
            const montant = this.getAttribute('data-montant');
            const type = this.getAttribute('data-type');

            // Remplir le modal avec les données
            document.getElementById('modalTransactionIdClient').textContent = transactionId;
            document.getElementById('modalNomEmetteur').textContent = nomEmetteur;
            document.getElementById('modalPrenomEmetteur').textContent = prenomEmetteur;
            document.getElementById('modalNomReceveur').textContent = nomReceveur;
            document.getElementById('modalPrenomReceveur').textContent = prenomReceveur;
            document.getElementById('modalTelephoneReceveur').textContent = telephoneReceveur;
            document.getElementById('modalDateClient').textContent = date;
            document.getElementById('modalMontantClient').textContent = montant;
            document.getElementById('modalTypeClient').textContent = type;
        });
    });
</script>



<script>
    function openModal() {
        document.getElementById('transferModal').style.display = 'block';
    }
    
    function closeModal() {
        document.getElementById('transferModal').style.display = 'none';
    }
    
    // Fermer le modal en cliquant en dehors de celui-ci
    window.onclick = function(event) {
        var modal = document.getElementById('transferModal');
        if (event.target == modal) {
            closeModal();
        }
    }
    
    document.getElementById('transferForm').addEventListener('submit', function(event) {
        const amountSent = parseFloat(document.getElementById('montant_envoye').value);
        const amountReceived = parseFloat(document.getElementById('montant_recu').value);
        const errorMessage = document.getElementById('errorMessage');
        const balanceErrorMessage = document.getElementById('balanceErrorMessage');
        const receivedAmountError = document.getElementById('receivedAmountError');
        const receivedBalanceErrorMessage = document.getElementById('receivedBalanceErrorMessage');
        const currentBalance = {{ $compte ? $compte->solde : 0 }}; // Récupérez le solde actuel
    
        // Réinitialiser les messages d'erreur
        errorMessage.style.display = 'none';
        balanceErrorMessage.style.display = 'none';
        receivedAmountError.style.display = 'none';
        receivedBalanceErrorMessage.style.display = 'none';
    
        // Vérifier si le montant envoyé est inférieur ou égal à 500
        if (amountSent <= 500) {
            event.preventDefault(); // Empêche la soumission du formulaire
            errorMessage.style.display = 'block'; // Affiche le message d'erreur
        } 
        // Vérifier si le montant envoyé dépasse le solde actuel
        else if (amountSent > currentBalance) {
            event.preventDefault(); // Empêche la soumission du formulaire
            balanceErrorMessage.style.display = 'block'; // Affiche le message d'erreur
        }
    
        // Vérifier si le montant reçu est inférieur ou égal à 500
        if (amountReceived <= 500) {
            event.preventDefault(); // Empêche la soumission du formulaire
            receivedAmountError.style.display = 'block'; // Affiche le message d'erreur
        }
    
        // Vérifier si le montant reçu dépasse le solde actuel
        if (amountReceived >= currentBalance) {
            event.preventDefault(); // Empêche la soumission du formulaire
            receivedBalanceErrorMessage.style.display = 'block'; // Affiche le message d'erreur
        }
    
        // Si tout est valide, mettre à jour les soldes ici
        // Tu peux ajouter une logique pour soustraire le montant envoyé et ajouter le montant reçu.
    });
    
    // Écouteurs d'événements pour mettre à jour automatiquement les champs
    document.getElementById('montant_envoye').addEventListener('input', function() {
        const amountSent = parseFloat(this.value);
        const amountReceivedField = document.getElementById('montant_recu');
        
        // Calculer le montant reçu
        if (!isNaN(amountSent)) {
                const amountReceived = Math.round(amountSent * 0.98);
            amountReceivedField.value = amountReceived.toFixed(2); // Mettre à jour le champ 'Montant reçu'
        } else {
            amountReceivedField.value = '';
        }
    
        // Vérifier et masquer les messages d'erreur en fonction du montant
        const errorMessage = document.getElementById('errorMessage');
        const balanceErrorMessage = document.getElementById('balanceErrorMessage');
    
        if (amountSent > 500 || isNaN(amountSent)) {
            errorMessage.style.display = 'none';
        } else {
            errorMessage.style.display = 'block'; // Montant invalide
        }
    
        const currentBalance = {{ $compte ? $compte->solde : 0 }};
        if (amountSent <= currentBalance || isNaN(amountSent)) {
            balanceErrorMessage.style.display = 'none';
        } else {
            balanceErrorMessage.style.display = 'block'; // Montant invalide
        }
    });
    
    // Écouteur d'événements pour mettre à jour le montant envoyé
    document.getElementById('montant_recu').addEventListener('input', function() {
        const amountReceived = parseFloat(this.value);
        const amountSentField = document.getElementById('montant_envoye');
    
        // Calculer le montant envoyé
        if (!isNaN(amountReceived)) {
            const amountSent = Math.round(amountReceived / 0.98);
            amountSentField.value = amountSent.toFixed(2); // Mettre à jour le champ 'Montant envoyé'
        } else {
            amountSentField.value = '';
        }
    
        // Vérifier et masquer les messages d'erreur en fonction du montant
        const receivedAmountError = document.getElementById('receivedAmountError');
        const receivedBalanceErrorMessage = document.getElementById('receivedBalanceErrorMessage');
    
        if (amountReceived > 500 || isNaN(amountReceived)) {
            receivedAmountError.style.display = 'none';
        } else {
            receivedAmountError.style.display = 'block'; // Montant invalide
        }
    
        const currentBalance = {{ $compte ? $compte->solde : 0 }};
        if (amountReceived < currentBalance || isNaN(amountReceived)) {
            receivedBalanceErrorMessage.style.display = 'none';
        } else {
            receivedBalanceErrorMessage.style.display = 'block'; // Montant invalide
        }
    });
    </script>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
@endsection