<?php

namespace App\Observers;

use App\Models\User;

class UserObserver
{
    public function creating(User $user)
    {
        if (! $user->role_id) {
            $user->role_id = getRoleIdBySlug('user');
        }
    }
}
