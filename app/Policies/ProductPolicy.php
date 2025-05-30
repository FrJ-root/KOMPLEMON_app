<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Product;
use Illuminate\Auth\Access\HandlesAuthorization;

class ProductPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return in_array($user->role, ['administrateur', 'gestionnaire_produits']);
    }

    public function view(User $user, Product $product): bool
    {
        return in_array($user->role, ['administrateur', 'gestionnaire_produits']);
    }

    public function create(User $user): bool
    {
        return in_array($user->role, ['administrateur', 'gestionnaire_produits']);
    }

    public function update(User $user, Product $product): bool
    {
        return in_array($user->role, ['administrateur', 'gestionnaire_produits']);
    }

    public function delete(User $user, Product $product): bool
    {
        return $user->role === 'administrateur';
    }
}
