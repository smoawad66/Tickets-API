<?php

namespace App\Policies\V1;

use App\Models\Ticket;
use App\Models\User;

class TicketPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }

    public function store(User $user, $author_id): bool
    {
        return $user->tokenCan('ticket:create') ||
            $user->tokenCan('ticket:own:create') && ($user->id == $author_id || $author_id == null);
    }

    public function replace(User $user): bool
    {
        return $user->tokenCan('ticket:replace');
    }

    public function update(User $user, Ticket $ticket): bool
    {
        return (
            $user->tokenCan('ticket:update') ||
            $user->tokenCan('ticket:own:update') && $user->id == $ticket->user_id
        );
    }

    public function delete(User $user, Ticket $ticket): bool
    {
        return (
            $user->tokenCan('ticket:delete') ||
            $user->tokenCan('ticket:own:delete') && $user->id == $ticket->user_id
        );
    }
}
