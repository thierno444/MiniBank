<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Compte;

class AddSoldeToComptes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Créer ou récupérer le compte pour l'utilisateur avec l'ID 10
        $compte1 = Compte::firstOrCreate(
            ['user_id' => 10],  // Cherche un compte avec l'ID utilisateur 10
            ['solde' => 100000.00]  // Crée un compte avec ce solde s'il n'existe pas
        );

        // Si le compte existait déjà, ajouter le solde
        if (!$compte1->wasRecentlyCreated) {
            $compte1->solde += 100000.00;  // Ajouter 100.000 FCFA
        }
        $compte1->save();

        // Créer ou récupérer le compte pour l'utilisateur avec l'ID 11
        $compte2 = Compte::firstOrCreate(
            ['user_id' => 11],  // Cherche un compte avec l'ID utilisateur 11
            ['solde' => 150000.00]  // Crée un compte avec ce solde s'il n'existe pas
        );

        // Si le compte existait déjà, ajouter le solde
        if (!$compte2->wasRecentlyCreated) {
            $compte2->solde += 150000.00;  // Ajouter 150.000 FCFA
        }
        $compte2->save();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Annuler les mises à jour en retirant les montants ajoutés
        $compte1 = Compte::where('user_id', 10)->first();
        if ($compte1) {
            $compte1->solde -= 100000.00;  // Retirer 100.000 FCFA
            $compte1->save();
        }

        $compte2 = Compte::where('user_id', 11)->first();
        if ($compte2) {
            $compte2->solde -= 150000.00;  // Retirer 150.000 FCFA
            $compte2->save();
        }
    }
}
