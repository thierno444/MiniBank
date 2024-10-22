<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUtilisateursTable extends Migration
{
    public function up()
    {
        Schema::create('utilisateurs', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->string('prenom');
            $table->string('telephone')->unique();
            $table->string('motdepasse');
            $table->date('dateNaissance');
            $table->string('adresse');
            $table->enum('type', ['client', 'distributeur', 'agent']); // Ajout du type
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('utilisateurs');
    }
}