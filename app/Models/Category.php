<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    use HasFactory;

    protected $table = 'categories';

    protected $fillable = [
        'description',
        'image_url',
        'nom',
    ];

    public function products(): HasMany
    {
        return $this->hasMany(Product::class, 'categorie_id');
    }
    
    public function getNameAttribute()
    {
        return $this->nom;
    }
}
