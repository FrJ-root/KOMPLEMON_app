<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Testimonial extends Model
{
    use HasFactory;

    protected $table = 'temoignages';

    protected $fillable = [
        'nom_client',
        'media_type',
        'media_url',
        'contenu',
        'statut',
    ];
    
    public function getMediaTypeAttribute($value)
    {
        if ($value) {
            return $value;
        }
        
        if (!$this->media_url) {
            return 'none';
        }
        
        if (strpos($this->media_url, 'youtube.com') !== false || strpos($this->media_url, 'youtu.be') !== false) {
            return 'youtube';
        }
        
        $extension = pathinfo($this->media_url, PATHINFO_EXTENSION);
        
        if (in_array($extension, ['jpg', 'jpeg', 'png', 'gif'])) {
            return 'image';
        }
        
        if (in_array($extension, ['mp4', 'avi', 'mov', 'wmv'])) {
            return 'video';
        }
        
        return 'none';
    }
    
    public function isApproved()
    {
        return $this->statut === 'approuvé';
    }
    
    public function hasMedia()
    {
        return !is_null($this->media_url);
    }
}