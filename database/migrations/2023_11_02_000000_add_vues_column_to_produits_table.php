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
        Schema::table('produits', function (Blueprint $table) {
            // Check if the column exists before trying to add it
            if (!Schema::hasColumn('produits', 'vues')) {
                $table->integer('vues')->default(0)->after('valeurs_nutritionnelles'); // Compteur de vues du produit
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('produits', function (Blueprint $table) {
            if (Schema::hasColumn('produits', 'vues')) {
                $table->dropColumn('vues');
            }
        });
    }
};
