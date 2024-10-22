<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAgentsTable extends Migration
{
    public function up()
    {
        Schema::create('agents', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('utilisateur_id'); // Relation avec Utilisateur
            $table->string('email')->unique();
            $table->timestamps();

            // Foreign key constraint
            $table->foreign('utilisateur_id')->references('id')->on('utilisateurs')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('agents');
    }
}