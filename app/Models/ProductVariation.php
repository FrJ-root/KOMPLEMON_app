<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductVariation extends Model
{
    use HasFactory;

    protected $fillable = [
        'stock_quantity',
        'product_id',
        'quantity',
        'flavor',
        'price',
        'size',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
