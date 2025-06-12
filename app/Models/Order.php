<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    use HasFactory;

    protected $table = 'commandes';

    protected $fillable = [
        'client_id',
        'date_commande',
        'statut',
        'total',
        'historique',
    ];

    protected $casts = [
        'date_commande' => 'datetime',
    ];

    /**
     * Get the client that owns the order
     */
    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class, 'client_id');
    }

    /**
     * Get the order details for the order
     */
    public function orderDetails(): HasMany
    {
        return $this->hasMany(OrderDetail::class, 'commande_id');
    }

    /**
     * Get the order items for the order
     */
    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class, 'commande_id');
    }
}
