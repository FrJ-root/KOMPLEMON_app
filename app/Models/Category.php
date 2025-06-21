<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Category extends Model{

    use HasFactory;

    protected $table = 'categories';

    protected $fillable = [
        'nom',
        'image_url',
        'description',
    ];

    public function getImageUrlAttribute($value)
    {
        if (!$value) {
            return null;
        }
        
        if (filter_var($value, FILTER_VALIDATE_URL)) {
            return $value;
        }
        
        if (strpos($value, 'storage/') === 0) {
            return asset($value);
        }
        
        return Storage::disk('public')->url($value);
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class, 'categorie_id');
    }
}