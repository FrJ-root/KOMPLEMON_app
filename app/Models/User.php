<?php

namespace App\Models;

use Filament\Panel;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Filament\Models\Contracts\FilamentUser;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements FilamentUser
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    protected $fillable = [
        'role',
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'password' => 'hashed',
        'blocked_permissions' => 'array',
        'email_verified_at' => 'datetime',
    ];

    public function canAccessPanel(Panel $panel): bool
    {
        return in_array($this->role, [
            'gestionnaire_commandes',
            'gestionnaire_produits',
            'editeur_contenu',
            'administrateur',
        ]);
    }

    public function hasRole(string $role): bool
    {
        return $this->role === $role;
    }

    public function isAdmin(): bool
    {
        return $this->role === 'administrateur';
    }

    public function isProductManager(): bool
    {
        return $this->role === 'gestionnaire_produits';
    }

    public function isOrderManager(): bool
    {
        return $this->role === 'gestionnaire_commandes';
    }

    public function isContentEditor(): bool
    {
        return $this->role === 'editeur_contenu';
    }

    public function hasPermission(string $permission): bool
    {
        if ($this->role === 'administrateur') {
            return true;
        }

        if (is_array($this->blocked_permissions) && in_array($permission, $this->blocked_permissions)) {
            return false;
        }

        return match ($this->role) {
            'gestionnaire_produits' => in_array($permission, [
                'manage_categories',
                'manage_products',
                'manage_media',
            ]),
            'gestionnaire_commandes' => in_array($permission, [
                'manage_orders',
                'export_orders',
                'manage_customers',
            ]),
            'editeur_contenu' => in_array($permission, [
                'manage_articles',
                'manage_testimonials',
            ]),
            default => false,
        };
    }
}