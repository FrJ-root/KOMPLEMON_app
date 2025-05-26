<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $table = 'clients';

    protected $fillable = [
        'city',
        'name',
        'email',
        'phone',
        'address',
        'country',
        'postal_code',
    ];

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }
}
