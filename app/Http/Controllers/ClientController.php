<?php

// app/Http/Controllers/ClientController.php

// app/Http/Controllers/ClientController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\Compte;
use App\Models\User;
use App\Models\Client;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Auth;

class ClientController extends Controller
{
    
    public function index()
    {
        // Récupérer l'utilisateur connecté
        $client = auth()->user();
    
        // Récupérer le compte associé à l'utilisateur
        $compte = Compte::where('user_id', $client->id)->first();
    
       // Contenu du QR code avec des informations structurées
       $clientInfo = "Nom: {$client->nom}\n" .
       "Prénom: {$client->prenom}\n" .
       "Téléphone: {$client->telephone}\n" .
       "Numéro de compte: {$client->num_compte}\n" .
       "Statut: " . ($client->blocked ? 'Bloqué' : 'Actif');



        // Récupération du solde actuel du compte du client
        $solde = $compte->solde;

        // Génération d'un code QR pour le numéro de compte du client
        $qrCodeImage = QrCode::format('png')->size(200)->generate($client->num_compte);


        // Récupérer les transactions du client
        $transactions = Transaction::where(function($query) use ($client) {
                $query->where('receveur_id', $client->id)
                      ->orWhere('emetteur_id', $client->id);
            })
            ->with('distributeur')
            ->orderBy('created_at', 'desc')
            ->get();
    
        // Passer les informations à la vue
        return view('transactions', [
            'client' => $client,
            'transactions' => $transactions,
            'numCompte' => $client->num_compte,
            'solde' => $solde,
            'compte' => $compte,
            'qrCode' => base64_encode($qrCodeImage), // Encodage du QR code en base64 pour affichage
        ]);
    }

// Méthode pour générer un QR code unique pour le client
public function generateQrCode()
{
    $client = Auth::user();
    $qrCodeImage = QrCode::format('png')->size(200)->generate($client->num_compte . '?' . time()); // Ajout d'un timestamp pour l'unicité
    return response()->json(['qrCode' => base64_encode($qrCodeImage)]); // Retourne le QR code en JSON
}


    public function search(Request $request)
    {
        $accountNumber = $request->input('account_number');
    
        // Rechercher le client dans la table users
        $client = User::where('num_compte', $accountNumber)->first();
    
        if ($client) {
            return response()->json($client);
        } else {
            return response()->json(['error' => 'Client non trouvé'], 404);
        }
    }

    public function transfer(Request $request)
{
    // Validation des données
    $request->validate([
        'numero_compte' => 'required|string', // Numéro de compte du récepteur
        'montant_envoye' => 'required|numeric|min:500', // Vérification que le montant est supérieur à 500
    ]);

    // Trouver le compte émetteur (l'utilisateur connecté)
    $emetteur = Compte::where('user_id', auth()->user()->id)->first();

    // Vérifier si le compte existe
    if (!$emetteur) {
        session(['message' => 'Votre compte n\'existe pas.', 'message_type' => 'error']);
        return redirect()->back();    }

    // Vérifier le solde du compte émetteur
    if ($emetteur->solde < $request->montant_envoye) {
        session(['message' => 'Votre solde est insuffisant pour effectuer ce transfert.', 'message_type' => 'error']);
        return redirect()->back();
        }

    // Trouver le compte récepteur en fonction du numéro de compte entré dans le formulaire
    $receveur = Compte::whereHas('user', function($query) use ($request) {
        $query->where('num_compte', $request->numero_compte);
    })->first();

    // Vérifier si le compte récepteur existe
    if (!$receveur) {
        session(['message' => 'Le compte récepteur n\'existe pas.', 'message_type' => 'error']);
        return redirect()->back();
        }

    // Calculer les frais de 2% et le montant reçu par le récepteur
    $frais = $request->montant_envoye * 0.02;
    $montant_recu = $request->montant_envoye - $frais;

    // Effectuer le transfert
    // Réduire le solde de l'émetteur
    $emetteur->solde -= $request->montant_envoye;
    $emetteur->save();

    // Augmenter le solde du récepteur
    $receveur->solde += $montant_recu;
    $receveur->save();

    

    // Créer la transaction pour le transfert
    Transaction::create([
        'emetteur_id' => auth()->user()->id,
        'receveur_id' => $receveur->user_id,
        'distributeur_id' => null, // Pas de distributeur dans ce cas
        'agent_id' => null, // Pas d'agent dans ce cas
        'type' => 'transfert',
        'montant' => $request->montant_envoye,
        'frais' => $frais,
        'statut' => 'completed',
    ]);

    session(['message' => 'Transfert effectué avec succès.', 'message_type' => 'success']);
    return redirect()->back();
}

}