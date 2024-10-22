<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionsTable extends Migration
{
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->float('montant');
            $table->float('frais')->nullable(); // Frais de transfert
            $table->float('bonus')->nullable(); // Bonus pour le distributeur
            $table->enum('type', ['depot', 'retrait', 'annulation']);
            $table->date('date');
            $table->enum('statut', ['en_attente', 'complétée', 'annulée'])->default('en_attente');
            $table->unsignedBigInteger('client_id')->nullable(); // Relation avec Client
            $table->unsignedBigInteger('distributeur_id')->nullable(); // Relation avec Distributeur
            $table->unsignedBigInteger('agent_id')->nullable(); // Relation avec Agent
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('client_id')->references('id')->on('clients')->onDelete('cascade');
            $table->foreign('distributeur_id')->references('id')->on('distributeurs')->onDelete('cascade');
            $table->foreign('agent_id')->references('id')->on('agents')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('transactions');
    }
}