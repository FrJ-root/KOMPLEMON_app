<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Product extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $table = 'produits';

    protected $fillable = [
        'valeurs_nutritionnelles',
        'seuil_alerte_stock',
        'categorie_id',
        'description',
        'suivi_stock',
        'ingredients',
        'prix_promo',
        'featured',
        'statut',
        'stock',
        'image',
        'vues',
        'prix',
        'nom',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'categorie_id');
    }

    public function productMedia(): HasMany
    {
        return $this->hasMany(ProductMedia::class, 'produit_id');
    }

    public function getStockStatus()
    {
        if (!isset($this->stock_quantity) || !isset($this->stock_threshold)) {
            return 'in_stock';
        }

        if ($this->stock_quantity <= 0) {
            return 'out_of_stock';
        }

        if ($this->stock_quantity <= $this->stock_threshold) {
            return 'low_stock';
        }

        return 'in_stock';
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('product-images')
            ->useDisk('public');
    }
}