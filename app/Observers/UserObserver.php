<?php

namespace App\Observers;

use App\Models\User;
use App\Models\Compte;

class UserObserver
{
    public function created(User $user)
    {
        // Vérifier si l'utilisateur est un client ou un distributeur
        if (in_array($user->role, ['client', 'distributeur'])) {
            Compte::create([
                'user_id' => $user->id,
                'solde' => 0,
            ]);
        }
    }
}