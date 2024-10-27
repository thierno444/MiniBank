@extends('layouts.sidebar-navbarC')

@section('content')

            <h2 class="titre" style="margin-left: 50px; margin-top: 20px;">Ma carte</h2>

           <!-- Bloc de la taille d'une carte bancaire -->
            <div class="carte" style="width: 800px; height: 280px; background-color: #f8f9fa; border: 1px solid #ced4da; border-radius: 20px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1); margin-left: 50px; margin-top: 20px;">
                <!-- Contenu du bloc (comme un numéro de carte, nom, etc.) -->
                <div class="p-3">
                    <p>Solde</p>
                    <h5>{{ $compte ? number_format($compte->solde, 2) : '0.00' }} FCFA</h5><br>
                    <p>Nom d'utilisateur</p>
                    <h5>{{ $client->prenom }} {{ $client->nom }}</h5>
                </div>

                <!-- Trait fin -->
                <hr style="border: 1px solid #ced4da; margin: 0;">

                <!-- Numéro de téléphone -->
                <div class="p-3 d-flex justify-content-between align-items-center">
                    <h5>{{ $client->telephone }}</h5>
                    <img src="{{ asset('images/num.png') }}" alt="Icône Téléphone" style="width: 70px; height: 50px; margin-left: 10px;">
                </div>


            </div>

           <!-- Bloc de transfert -->
            <div class="transfer-button" style="width: 300px; height: 120px; background-color: #2D60FF; color: white; border-radius: 40px; display: flex; align-items: center; justify-content: center; margin-left: 1000px; margin-top: -200px;">
                <img src="{{ asset('images/transferer.png') }}" alt="Transférer" style="width: 40px; height: 40px; margin-right: 10px;">
                <h5 style="margin: 0;">Transférer</h5>
            </div>


        </div>
    </div>
    <!-- Titre des transactions -->
    <h3 style="margin-left: 300px; margin-top: -440px;">Liste des Transactions</h3>

    <div class="transactions" style="width: 1500px; height: 350px; background-color: #f8f9fa; border: 1px solid #ced4da; border-radius: 20px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1); margin-left: 300px; margin-top: 5px; padding: 20px; overflow-y: auto;">
    <table class="table table-bordered" style="min-width: 100%;">
        <thead>
            <tr>
                <th>Nom Distributeur</th>
                <th>Prénom Distributeur</th>
                <th>ID Transaction</th>
                <th>Téléphone Distributeur</th>
                <th>Date</th>
                <th>Montant</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($transactions as $transaction)
                <tr>
                    <td>{{ $transaction->distributeur->nom }}</td>
                    <td>{{ $transaction->distributeur->prenom }}</td>
                    <td>{{ $transaction->id }}</td>
                    <td>{{ $transaction->distributeur->telephone }}</td>
                    <td>{{ $transaction->created_at->format('Y-m-d') }}</td>
                    <td style="color: {{ $transaction->type == 'depot' ? 'green' : 'red' }};">
                        {{ $transaction->type == 'depot' ? '+' : '-' }}{{ number_format($transaction->mountant, 2) }} FCFA
                    </td>                          
                    <td>
                        <button type="button" class="btn btn-link" data-bs-toggle="modal" data-bs-target="#factureModalClient"
                                data-id="{{ $transaction->id }}"
                                data-nom-distributeur="{{ $transaction->distributeur->nom }}"
                                data-prenom-distributeur="{{ $transaction->distributeur->prenom }}"
                                data-telephone-distributeur="{{ $transaction->distributeur->telephone }}"
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
        <!-- Modal Facture Client -->
        <div class="modal fade" id="factureModalClient" tabindex="-1" aria-labelledby="factureModalClientLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="factureModalClientLabel">Détails de la Facture</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <strong>ID Transaction :</strong> <span id="modalTransactionIdClient"></span><br>
                        <strong>Nom Distributeur :</strong> <span id="modalNomDistributeur"></span><br>
                        <strong>Prénom Distributeur :</strong> <span id="modalPrenomDistributeur"></span><br>
                        <strong>Téléphone :</strong> <span id="modalTelephoneDistributeur"></span><br>
                        <strong>Date :</strong> <span id="modalDateClient"></span><br>
                        <strong>Montant :</strong> <span id="modalMontantClient"></span><br>
                        <strong>Type :</strong> <span id="modalTypeClient"></span><br>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                    </div>
                </div>
            </div>
        </div>

        <script>
            // Événement au clic sur le bouton "Voir Facture" pour le client
            document.querySelectorAll('button[data-bs-target="#factureModalClient"]').forEach(button => {
                button.addEventListener('click', function() {
                    // Récupérer les données des attributs data-*
                    const transactionId = this.getAttribute('data-id');
                    const nomDistributeur = this.getAttribute('data-nom-distributeur');
                    const prenomDistributeur = this.getAttribute('data-prenom-distributeur');
                    const telephoneDistributeur = this.getAttribute('data-telephone-distributeur');
                    const date = this.getAttribute('data-date');
                    const mountant = this.getAttribute('data-montant');
                    const type = this.getAttribute('data-type');

                    // Remplir le modal avec les données
                    document.getElementById('modalTransactionIdClient').textContent = transactionId;
                    document.getElementById('modalNomDistributeur').textContent = nomDistributeur;
                    document.getElementById('modalPrenomDistributeur').textContent = prenomDistributeur;
                    document.getElementById('modalTelephoneDistributeur').textContent = telephoneDistributeur;
                    document.getElementById('modalDateClient').textContent = date;
                    document.getElementById('modalMontantClient').textContent = mountant;
                    document.getElementById('modalTypeClient').textContent = type;
                });
            });
        </script>


        <!-- Pagination -->
        <div class="pagination" style="margin-top: 20px; display: flex; justify-content: flex-end; margin-right: 50px;">
        <a href="#" style="color: #2D60FF; text-decoration: none; margin-right: 10px;">
            <i class="bi bi-arrow-left"></i> Précédent
        </a>
        <div style="margin: 0 10px;">
            <a href="#" style="cursor: pointer; color: #2D60FF; text-decoration: none;">1</a>
            <a href="#" style="cursor: pointer; margin: 0 5px; color: #2D60FF; text-decoration: none;">2</a>
            <a href="#" style="cursor: pointer; margin: 0 5px; color: #2D60FF; text-decoration: none;">3</a>
            <a href="#" style="cursor: pointer; margin: 0 5px; color: #2D60FF; text-decoration: none;">4</a>
        </div>
        <a href="#" style="color: #2D60FF; text-decoration: none; margin-left: 10px;">
            Suivant <i class="bi bi-arrow-right"></i>
        </a>
</div>


    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
>   @endsection


