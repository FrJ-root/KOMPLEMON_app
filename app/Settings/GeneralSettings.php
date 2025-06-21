<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class GeneralSettings extends Settings
{
    public string $site_name;
    public string $site_url;
    public ?string $site_logo;
    public ?string $site_description;
    
    public ?string $contact_email;
    public ?string $contact_phone;
    public ?string $contact_address;
    
    public ?string $social_facebook;
    public ?string $social_instagram;
    public ?string $social_twitter;
    public ?string $social_youtube;
    
    public ?string $meta_description;
    public ?string $meta_keywords;
    public ?string $google_analytics_id;

    public static function group(): string
    {
        return 'general';
    }
    
    public static function defaultSettings(): array
    {
        return [
            'site_name' => 'KOMPLEMON',
            'site_url' => 'https://komplemon.com',
            'site_logo' => null,
            'site_description' => 'Compléments alimentaires et produits bien-être',
            
            'contact_email' => 'contact@komplemon.com',
            'contact_phone' => null,
            'contact_address' => null,
            
            'social_facebook' => null,
            'social_instagram' => null,
            'social_twitter' => null,
            'social_youtube' => null,
            
            'meta_description' => null,
            'meta_keywords' => null,
            'google_analytics_id' => null,
        ];
    }
}
