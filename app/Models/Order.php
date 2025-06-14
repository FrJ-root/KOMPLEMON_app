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
        'historique'
    ];
    
    protected $casts = [
        'date_commande' => 'datetime',
        'total' => 'decimal:2'
    ];
    
    /**
     * Get the client that owns the order
     */
    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class, 'client_id');
    }

    /**
     * Get the order items for the order
     */
    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class, 'commande_id');
    }
    
    /**
     * Update order status and add to history
     */
    public function updateStatus(string $newStatus, string $userName)
    {
        if ($this->statut === $newStatus) {
            return false;
        }
        
        $oldStatus = $this->statut;
        $historyEntry = now()->format('Y-m-d H:i:s') . " - Statut changé de '{$oldStatus}' à '{$newStatus}' par {$userName}\n";
        
        $this->historique = ($this->historique ?? '') . $historyEntry;
        $this->statut = $newStatus;
        
        return $this->save();
    }
    
    /**
     * Get formatted history entries
     */
    public function getHistoryEntries(): array
    {
        if (empty($this->historique)) {
            return [];
        }
        
        return array_filter(explode("\n", $this->historique));
    }
    
    /**
     * Calculate total from items
     */
    public function calculateTotal(): float
    {
        return $this->items->sum(function ($item) {
            return $item->quantite * $item->prix_unitaire;
        });
    }
}
