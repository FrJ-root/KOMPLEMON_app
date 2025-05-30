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
        Schema::create('promotions', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->enum('type', ['pourcentage', 'montant']);
            $table->decimal('valeur', 10, 2);
            $table->datetime('date_debut');
            $table->datetime('date_fin');
            $table->boolean('utilisation_unique')->default(false);
            $table->integer('nombre_utilisations')->default(0);
            $table->integer('limite_utilisations')->nullable();
            $table->text('description')->nullable();
            $table->boolean('actif')->default(true);
            $table->json('produits_applicables')->nullable(); // Specific products the promo applies to
            $table->json('categories_applicables')->nullable(); // Specific categories the promo applies to
            $table->decimal('montant_minimum', 10, 2)->nullable(); // Minimum order amount
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('promotions');
    }
};
