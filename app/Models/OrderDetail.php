<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;

class OrderDetail extends Model
{
    use HasFactory;

    protected $table = 'details_commandes';

    protected $fillable = [
        'prix_unitaire',
        'commande_id',
        'produit_id',
        'quantite',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'commande_id');
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'produit_id');
    }
}
