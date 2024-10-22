<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDistributeursTable extends Migration
{
    public function up()
    {
        Schema::create('distributeurs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('utilisateur_id'); // Relation avec Utilisateur
            $table->integer('numCarteIdentite')->unique();
            $table->float('solde')->default(0);
            $table->boolean('statut')->default(true);
            $table->string('photo')->nullable();
            $table->integer('numeroCompte')->unique();
            $table->timestamps();

            // Foreign key constraint
            $table->foreign('utilisateur_id')->references('id')->on('utilisateurs')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('distributeurs');
    }
}