<?php

namespace App\Policies;

use App\Models\Movie;
use App\Models\User;

class MoviePolicy
{
    /**
     * Create a new policy instance.
     */
    public function viewAny(User $user)
    {
        return $user->type == 'A';
    }

    public function view(User $user, Movie $movie): bool
    {
        return $user->type == 'A';
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->type == 'A';
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Movie $movie): bool
    {
        return $user->type == 'A';
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Movie $movie): bool
    {
        return $user->type == 'A';
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Movie $movie): bool
    {
        return $user->type == 'A';
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Movie $movie): bool
    {
        return $user->type == 'A';
    }
}
