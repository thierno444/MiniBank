<?php

// app/Http/Controllers/ClientController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\Compte;
use App\Models\User;
use App\Models\Client;


class ClientController extends Controller
{
   

    public function index()
{
    // Récupérer l'utilisateur connecté
    $client = auth()->user();


    // Récupérer le compte associé à l'utilisateur
    $compte = Compte::where('user_id', $client->id)->first();


    // Récupérer les transactions où le client est le receveur ou l'émetteur
    $transactions = Transaction::where(function($query) use ($client) {
        $query->where('receveur_id', $client->id)
              ->orWhere('emettteur_id', $client->id);
    })
    ->with('distributeur') // Charge les détails du distributeur
    ->orderBy('created_at', 'desc') // Tri par date décroissante
    ->get();

    // Passer les informations à la vue
    return view('transactions', [
        'client' => $client,
        'transactions' => $transactions,
        'compte' => $compte,
    ]);
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
}