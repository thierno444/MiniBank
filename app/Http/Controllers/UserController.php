<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Compte;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

    public function show($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['error' => 'Utilisateur non trouvé'], 404);
        }

        return view('users.show', compact('user'));
    }

    public function getCompte($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['error' => 'Utilisateur non trouvé'], 404);
        }

        $compte = Compte::where('user_id', $user->id)->first();

        if ($compte) {
            return response()->json($compte);
        } else {
            return response()->json(['error' => 'Compte non trouvé'], 404);
        }
    }

    public function searchUser(Request $request)
    {
    $numCompte = $request->input('num_compte');
    $user = User::where('num_compte', $numCompte)->first();

    if ($user) {
        return response()->json([
            'status' => 'found',
            'user' => [
                'id' => $user->id,
                'nom' => $user->nom,
                'prenom' => $user->prenom,
                'telephone' => $user->telephone,
                'email' => $user->email,
                'num_compte' => $user->num_compte,
                'carte_identite' => $user->carte_identite,
                'blocked' => $user->blocked,
            ]
        ]);
    } else {
        return response()->json(['status' => 'not_found']);
    }
    }

    public function listUsers()
    {
        $agentId = Auth::id();

        // Récupérer les clients et distributeurs actifs ayant effectué des transactions
        $clientsActifs = User::where('role', 'client')
            ->where('blocked', false)
            ->whereHas('transactions', fn($q) => $q->where('agent_id', $agentId))
            ->paginate(5, ['*'], 'clients_actifs');

        $distributeursActifs = User::where('role', 'distributeur')
            ->where('blocked', false)
            ->whereHas('transactions', fn($q) => $q->where('agent_id', $agentId))
            ->paginate(5, ['*'], 'distributeurs_actifs');

        $clientsBloques = User::where('role', 'client')
            ->where('blocked', true)
            ->whereHas('transactions', fn($q) => $q->where('agent_id', $agentId))
            ->paginate(5, ['*'], 'clients_bloques');

        $distributeursBloques = User::where('role', 'distributeur')
            ->where('blocked', true)
            ->whereHas('transactions', fn($q) => $q->where('agent_id', $agentId))
            ->paginate(5, ['*'], 'distributeurs_bloques');

        return view('Agent.user', compact('clientsActifs', 'distributeursActifs', 'clientsBloques', 'distributeursBloques'));
    }

    public function blockUser($id)
    {
        $user = User::findOrFail($id);
        $user->blocked = true;
        $user->save();

        return redirect()->route('agent.users')->with('success', 'Utilisateur bloqué.');
    }

    public function unblockUser($id)
    {
        $user = User::findOrFail($id);
        $user->blocked = false;
        $user->save();

        return redirect()->route('agent.users')->with('success', 'Utilisateur débloqué.');
    }
}
