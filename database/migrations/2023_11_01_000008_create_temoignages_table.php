<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('temoignages', function (Blueprint $table) {
            $table->id();
            $table->string('nom_client', 255);
            $table->text('contenu');
            $table->string('media_url', 255)->nullable();
            $table->string('media_type', 20)->default('none'); // 'image', 'video', 'youtube', 'none'
            $table->enum('statut', ['approuvé', 'en attente'])->default('en attente');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('temoignages');
    }
};
