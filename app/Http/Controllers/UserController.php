<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Compte; // Assurez-vous que ce modèle existe
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function create()
    {
        return view('users.create'); // Retourne la vue de création d'utilisateur
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'prenom' => 'required|string|max:255',
            'nom' => 'required|string|max:255',
            'telephone' => 'required|string|unique:users',
            'email' => 'nullable|email|unique:users',
            'adresse' => 'required|string|max:255',
            'carte_identite' => 'required|string|unique:users',
            'date_naissance' => 'required|date|before:today',
            'password' => 'required|string|min:8',
            'role' => 'required|in:agent,distributeur,client',
        ]);

        User::create($validatedData);

        return redirect()->route('welcome')->with('success', 'Utilisateur créé avec succès.');
    }

    // Méthode pour afficher un utilisateur
    public function show($id)
    {
        $distributeur = User::find($id); // Récupère l'utilisateur par ID

        if (!$distributeur) {
            // Gérer le cas où l'utilisateur n'existe pas
            return response()->json(['error' => 'Utilisateur non trouvé'], 404);
        }

        // Si l'utilisateur existe, retourner la vue ou les informations
        return view('users.show', compact('distributeur'));
    }

    // Méthode pour récupérer le compte d'un utilisateur
    public function getCompte($id)
    {
        $distributeur = User::find($id);

        if ($distributeur) {
            $compte = Compte::where('user_id', $distributeur->id)->first();
            
            if ($compte) {
                return response()->json($compte);
            } else {
                return response()->json(['error' => 'Compte non trouvé'], 404);
            }
        } else {
            return response()->json(['error' => 'Utilisateur non trouvé'], 404);
        }
    }
}