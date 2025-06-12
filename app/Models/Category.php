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
        'description',
        'image_url',
    ];

    // Add an accessor to get the full image URL
    public function getImageUrlAttribute($value)
    {
        if (!$value) {
            return null;
        }
        
        // Check if the value already starts with http or https
        if (filter_var($value, FILTER_VALIDATE_URL)) {
            return $value;
        }
        
        // Check if the value is already a storage path
        if (strpos($value, 'storage/') === 0) {
            return asset($value);
        }
        
        // Otherwise, construct the URL with storage path
        return Storage::disk('public')->url($value);
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class, 'categorie_id');
    }
}