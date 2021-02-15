<?php

namespace App\Policies;

use App\Reply;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    /**
     * Determine wheter the user can update the given profile
     *
     * @param User $user
     * @param User $signedInUser
     * @return bool
     */
    public function update(User $user, User $signedInUser)
    {
        return $signedInUser->id == $user->id;
    }
}
