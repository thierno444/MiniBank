<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class RegisteredUserController extends Controller
{
    /**
     * Afficher le formulaire d'inscription.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('auth.register'); // Retourner la vue du formulaire d'inscription
    }

    /**
     * Enregistrer un nouvel utilisateur.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // Valider les données du formulaire
        $request->validate([
            'prenom' => ['required', 'string', 'max:255'],
            'nom' => ['required', 'string', 'max:255'],
            'telephone' => ['required', 'string', 'max:15', 'unique:users,telephone'],
            'carte_identite' => ['required', 'string', 'max:20', 'unique:users,carte_identite'],
            'adresse' => ['required', 'string', 'max:255'],
            'photo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
            'date_naissance' => ['required', 'date'],
            'role' => ['required', 'string', 'in:agent,distributeur,client'], // Sélection parmi des valeurs valides
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        // Traitement de la photo
           // Traitement de la photo
    $path = $request->hasFile('photo') 
    ? $request->file('photo')->store('images', 'public') 
    : 'images/default.png';

        // Créer l'utilisateur
        $user = User::create([
            'prenom' => $request->prenom,
            'nom' => $request->nom,
            'telephone' => $request->telephone,
            'carte_identite' => $request->carte_identite,
            'adresse' => $request->adresse,
            'date_naissance' => $request->date_naissance,
            'role' => $request->role,
            'password' => Hash::make($request->password), // Hachage sécurisé du mot de passe
            'photo' => $path,
        ]);

        // Connecter l'utilisateur après l'inscription
        auth()->login($user);

        // Rediriger vers le tableau de bord avec un message de succès
        return redirect()->route('dashboard.client')->with('success', $user->prenom . ' a été créé avec succès.');

    }
}