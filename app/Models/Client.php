<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Client extends Model
{
    use HasFactory;

    protected $table = 'clients';

    protected $fillable = [
        'nom',
        'adresse',
        'email',
        'telephone',
    ];

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class, 'client_id');
    }
}
