<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // 'nom' in the requirements
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password'); // 'mot_de_passe' in the requirements
            $table->enum('role', ['administrateur', 'gestionnaire_produits', 'gestionnaire_commandes', 'editeur_contenu'])
                ->default('editeur_contenu');
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
