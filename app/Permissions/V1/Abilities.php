<?php


namespace App\Permissions\V1;

use App\Models\User;

final class Abilities
{
    public static function getAbilities(User $user): array
    {
        return $user->is_manager ? [
            'ticket:create',
            'ticket:update',
            'ticket:replace',
            'ticket:delete',
        ] : [
            'ticket:own:create',
            'ticket:own:update',
            'ticket:own:delete',
        ];
    }
}
