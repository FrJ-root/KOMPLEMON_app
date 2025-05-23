<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Customer extends Model
{
    use HasFactory;

    // Specify the table name
    protected $table = 'clients';

    protected $fillable = [
        'name',
        'email',
        'phone',
        'address',
        'city',
        'postal_code',
        'country',
    ];

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }
}
