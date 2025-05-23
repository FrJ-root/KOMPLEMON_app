<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductVariation extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'size',
        'quantity',
        'flavor',
        'price',
        'stock_quantity',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
