<?php

namespace App\Policies;

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
}
