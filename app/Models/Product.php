<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    
    protected $table = 'produits';

    protected $fillable = [
        'name',
        'status',
        'sale_price',
        'description',
        'category_id',
        'ingredients',
        'regular_price',
        'stock_quantity',
        'nutritional_values',
    ];

    protected $casts = [
        'ingredients' => 'array',
        'nutritional_values' => 'array',
    ];

    public function category(){
        return $this->belongsTo(Category::class);
    }

    public function variations(){
        return $this->hasMany(ProductVariation::class);
    }
}