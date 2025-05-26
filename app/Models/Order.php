<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $table = 'commandes';

    protected $fillable = [
        'date_commande',
        'historique',
        'client_id',
        'statut',
        'total',
    ];

    public function getStatusAttribute()
    {
        return $this->statut;
    }

    public function getTotalAmountAttribute()
    {
        return $this->total;
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'client_id');
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class, 'commande_id');
    }

    public function statusHistory(): HasMany
    {
        return $this->hasMany(OrderStatusHistory::class, 'commande_id');
    }
}
