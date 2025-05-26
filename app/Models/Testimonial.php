<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Testimonial extends Model
{
    use HasFactory;
    
    protected $table = 'temoignages';

    protected $fillable = [
        'customer_name',
        'content',
        'rating',
        'status',
    ];
}