<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('articles_blog', function (Blueprint $table) {
            $table->id();
            $table->string('titre', 255);
            $table->text('contenu');
            $table->string('categorie', 100)->nullable();
            $table->string('tags', 255)->nullable();
            $table->enum('statut', ['publié', 'brouillon'])->default('brouillon');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('articles_blog');
    }
};
