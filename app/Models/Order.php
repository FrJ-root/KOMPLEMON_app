<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    use HasFactory;

    // Specify the table name
    protected $table = 'commandes';

    protected $fillable = [
        'client_id', // Changed from customer_id to match DB schema
        'date_commande', // Order date field
        'statut', // Instead of status
        'total', // Instead of total_amount
        'historique', // History field
    ];

    // Mapping for more intuitive property access
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
        return $this->belongsTo(Customer::class, 'client_id'); // Specify the foreign key
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class, 'commande_id'); // Specify the foreign key
    }

    public function statusHistory(): HasMany
    {
        return $this->hasMany(OrderStatusHistory::class, 'commande_id'); // Specify the foreign key
    }
}
