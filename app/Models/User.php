<?php

namespace App\Models;

use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements FilamentUser
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'blocked_permissions' => 'array',
    ];

    public function canAccessPanel(Panel $panel): bool
    {
        return in_array($this->role, [
            'administrateur',
            'gestionnaire_produits',
            'gestionnaire_commandes',
            'editeur_contenu'
        ]);
    }

    /**
     * Check if user has a specific role
     */
    public function hasRole(string $role): bool
    {
        return $this->role === $role;
    }

    /**
     * Check if user has admin role
     */
    public function isAdmin(): bool
    {
        return $this->role === 'administrateur';
    }

    /**
     * Check if user is product manager
     */
    public function isProductManager(): bool
    {
        return $this->role === 'gestionnaire_produits';
    }

    /**
     * Check if user is order manager
     */
    public function isOrderManager(): bool
    {
        return $this->role === 'gestionnaire_commandes';
    }

    /**
     * Check if user is content editor
     */
    public function isContentEditor(): bool
    {
        return $this->role === 'editeur_contenu';
    }

    /**
     * Check if user has a specific permission
     */
    public function hasPermission(string $permission): bool
    {
        // Administrators have all permissions
        if ($this->role === 'administrateur') {
            return true;
        }

        // Check if permission is blocked for this user
        if (is_array($this->blocked_permissions) && in_array($permission, $this->blocked_permissions)) {
            return false;
        }

        // Check role-based permissions
        return match ($this->role) {
            'gestionnaire_produits' => in_array($permission, [
                'manage_products',
                'manage_categories',
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
