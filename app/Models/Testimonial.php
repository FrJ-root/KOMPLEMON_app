<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
// Temporarily commented out
// use Spatie\MediaLibrary\HasMedia;
// use Spatie\MediaLibrary\InteractsWithMedia;

class Testimonial extends Model
{
    use HasFactory;
    // Remove InteractsWithMedia trait
    
    protected $table = 'temoignages';

    protected $fillable = [
        'customer_name',
        'content',
        'rating',
        'status', // 'approved', 'pending', 'rejected'
    ];
}
