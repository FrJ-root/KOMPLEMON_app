<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('produits', function (Blueprint $table) {
            $table->id();
            $table->string('nom', 255);
            $table->text('description')->nullable();
            $table->foreignId('categorie_id')->nullable()->constrained('categories')->onDelete('set null');
            $table->decimal('prix', 10, 2);
            $table->decimal('prix_promo', 10, 2)->nullable();
            $table->string('image')->nullable();
            $table->json('variations_taille')->nullable();
            $table->json('variations_quantite')->nullable();
            $table->json('variations_saveurs')->nullable();
            $table->integer('stock')->default(0);
            $table->integer('vues')->default(0);
            $table->integer('seuil_alerte_stock')->default(5);
            $table->boolean('suivi_stock')->default(true);
            $table->text('ingredients')->nullable();
            $table->text('valeurs_nutritionnelles')->nullable();
            $table->boolean('featured')->default(false);
            $table->enum('statut', ['publié', 'brouillon'])->default('brouillon');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('produits');
    }
};
