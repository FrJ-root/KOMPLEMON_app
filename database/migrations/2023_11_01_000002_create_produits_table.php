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
        Schema::create('produits', function (Blueprint $table) {
            $table->id();
            $table->string('nom', 255);
            $table->text('description')->nullable();
            $table->foreignId('categorie_id')->nullable()->constrained('categories')->onDelete('set null');
            $table->decimal('prix', 10, 2);
            $table->decimal('prix_promo', 10, 2)->nullable();
            $table->integer('stock')->default(0);
            $table->text('ingredients')->nullable();
            $table->text('valeurs_nutritionnelles')->nullable();
            $table->enum('statut', ['publié', 'brouillon'])->default('brouillon');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('produits');
    }
};
