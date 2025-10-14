<?php

namespace App\Policies;

use App\Models\SuspiciousLogin;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class SuspiciousLoginPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return $user->is_super_admin;
    }

    public function view(User $user, SuspiciousLogin $suspiciousLogin)
    {
        return $user->is_super_admin;
    }

    public function markAsNotified(User $user, SuspiciousLogin $suspiciousLogin)
    {
        return $user->is_super_admin;
    }
}