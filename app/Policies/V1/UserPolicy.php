<?php

namespace App\Policies\V1;

use App\Models\Ticket;
use App\Models\User;

class UserPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }

    public function store(User $user): bool
    {
        return $user->tokenCan('user:create');
    }

    public function replace(User $user): bool
    {
        return $user->tokenCan('user:replace');
    }

    public function update(User $user): bool
    {
        return $user->tokenCan('user:update');
    }

    public function destroy(User $user): bool
    {
        return $user->tokenCan('user:delete');
    }

}
