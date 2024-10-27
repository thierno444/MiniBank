<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Compte;

class UpdateSoldeComptes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Mettre à jour le solde pour l'utilisateur avec l'ID 10
        $compte1 = Compte::where('user_id', 10)->first();
        if ($compte1) {
            $compte1->solde += 100000.00; // Ajouter 100.000 FCFA
            $compte1->save();
        }

        // Mettre à jour le solde pour l'utilisateur avec l'ID 11
        $compte2 = Compte::where('user_id', 11)->first();
        if ($compte2) {
            $compte2->solde += 150000.00; // Ajouter 150.000 FCFA
            $compte2->save();
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Si nécessaire, vous pouvez aussi définir comment annuler cette migration.
        // Par exemple, soustraire les montants ajoutés
        $compte1 = Compte::where('user_id', 10)->first();
        if ($compte1) {
            $compte1->solde -= 100000.00; // Retirer 100.000 FCFA
            $compte1->save();
        }

        $compte2 = Compte::where('user_id', 11)->first();
        if ($compte2) {
            $compte2->solde -= 150000.00; // Retirer 150.000 FCFA
            $compte2->save();
        }
    }
}
