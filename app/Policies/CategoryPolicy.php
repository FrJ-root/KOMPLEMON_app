<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Category;
use Illuminate\Auth\Access\HandlesAuthorization;

class CategoryPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->hasPermission('manage_categories');
    }

    public function view(User $user, Category $category): bool
    {
        return $user->hasPermission('manage_categories');
    }

    public function create(User $user): bool
    {
        return $user->hasPermission('manage_categories');
    }

    public function update(User $user, Category $category): bool
    {
        return $user->hasPermission('manage_categories');
    }

    public function delete(User $user, Category $category): bool
    {
        return $user->hasPermission('manage_categories');
    }

    public function restore(User $user, Category $category): bool
    {
        return $user->hasPermission('manage_categories');
    }

    public function forceDelete(User $user, Category $category): bool
    {
        return $user->hasPermission('manage_categories');
    }
}
