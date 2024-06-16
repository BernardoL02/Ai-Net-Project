<?php

namespace App\Policies;

use App\Models\Screening;
use App\Models\User;

class ScreeningPolicy
{
    /**
     * Create a new policy instance.
     */
    public function viewAny(User $user)
    {
        return true;
    }

    public function view(User $user, Screening $screening): bool
    {
        return true;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Screening $screening): bool
    {
        return $user->type == 'A';
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Screening $screening): bool
    {
        return $user->type == 'A';
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Screening $screening): bool
    {
        return $user->type == 'A';
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Screening $screening): bool
    {
        return $user->type == 'A';
    }
}
