<?php

namespace App\Policies;

use App\Models\User;
use App\Models\ProductMedia;
use Illuminate\Auth\Access\HandlesAuthorization;

class MediaPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->hasPermission('manage_media');
    }

    public function view(User $user, ProductMedia $media): bool
    {
        return $user->hasPermission('manage_media');
    }

    public function create(User $user): bool
    {
        return $user->hasPermission('manage_media');
    }

    public function update(User $user, ProductMedia $media): bool
    {
        return $user->hasPermission('manage_media');
    }

    public function delete(User $user, ProductMedia $media): bool
    {
        return $user->hasPermission('manage_media');
    }

    public function restore(User $user, ProductMedia $media): bool
    {
        return $user->hasPermission('manage_media');
    }

    public function forceDelete(User $user, ProductMedia $media): bool
    {
        return $user->hasPermission('manage_media');
    }
}
