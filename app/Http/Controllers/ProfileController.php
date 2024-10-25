<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        

        $user = $request->user();

        // Valide les champs du formulaire
        $validatedData = $request->validated();

        
        // Remplir les champs du modèle utilisateur
        $user->nom = $validatedData['nom'];
        $user->prenom = $validatedData['prenom'];
        $user->date_naissance = $validatedData['date_naissance'];
        $user->adresse = $validatedData['adresse'];

        // Gestion de la photo de profil
        if ($request->hasFile('photo')) {
            if ($user->photo) {
                Storage::disk('public')->delete($user->photo); // Supprimer l'ancienne image
            }
            $user->photo = $request->file('photo')->store('photo', 'public'); // Sauvegarder la nouvelle image
        }

        // Modification du mot de passe si rempli
        if ($request->filled('current_password') && $request->filled('password')) {
            // Vérifie si l'ancien mot de passe est correct
            if (Hash::check($request->input('current_password'), $user->password)) {
                $user->password = Hash::make($validatedData['password']);
            } else {
                return Redirect::back()->withErrors(['current_password' => 'L\'ancien mot de passe est incorrect']);
            }
        }

        

        // Sauvegarder les modifications
        $user->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }
}
