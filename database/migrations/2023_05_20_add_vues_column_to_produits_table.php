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
            // Add vues (views) column if it doesn't exist
            if (!Schema::hasColumn('produits', 'vues')) {
                $table->integer('vues')->default(0)->after('stock');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('produits', function (Blueprint $table) {
            // Remove the column when rolling back
            if (Schema::hasColumn('produits', 'vues')) {
                $table->dropColumn('vues');
            }
        });
    }
};
