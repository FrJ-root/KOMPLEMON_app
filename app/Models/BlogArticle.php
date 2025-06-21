<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BlogArticle extends Model{

    use HasFactory;

    protected $table = 'articles_blog';

    protected $fillable = [
        'categorie',
        'contenu',
        'statut',
        'titre',
        'tags',
    ];
}