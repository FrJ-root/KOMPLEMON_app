<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;

class Client extends Model{

    use HasFactory;

    protected $table = 'clients';

    protected $fillable = [
        'nom',
        'email',
        'adresse',
        'telephone',
    ];

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class, 'client_id');
    }
}