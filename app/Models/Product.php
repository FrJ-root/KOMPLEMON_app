<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use HasFactory;

    protected $table = 'produits';

    protected $fillable = [
        'nom',
        'description',
        'categorie_id',
        'prix',
        'prix_promo',
        'stock',
        'ingredients',
        'valeurs_nutritionnelles',
        'statut',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'categorie_id');
    }

    public function media(): HasMany
    {
        return $this->hasMany(ProductMedia::class, 'produit_id');
    }

    /**
     * Get the stock status of the product
     *
     * @return string
     */
    public function getStockStatus()
    {
        if (!isset($this->stock_quantity) || !isset($this->stock_threshold)) {
            return 'in_stock'; // Default status
        }

        if ($this->stock_quantity <= 0) {
            return 'out_of_stock';
        }

        if ($this->stock_quantity <= $this->stock_threshold) {
            return 'low_stock';
        }

        return 'in_stock';
    }
}