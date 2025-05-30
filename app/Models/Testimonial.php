<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Testimonial extends Model
{
    use HasFactory;

    protected $table = 'temoignages';

    protected $fillable = [
        'nom_client',
        'contenu',
        'media_url',
        'statut',
    ];
}