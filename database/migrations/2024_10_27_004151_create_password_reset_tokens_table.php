<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePasswordResetTokensTable extends Migration
{
    public function up()
    {
        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->id();
            $table->string('telephone')->index(); // Numéro de téléphone
            $table->string('token')->unique(); // Token de réinitialisation, unique
            $table->timestamp('created_at')->nullable(); // Date de création
            $table->timestamp('expires_at')->nullable(); // Date d'expiration (optionnel)
        });
    }

    public function down()
    {
        Schema::dropIfExists('password_reset_tokens');
    }
}

