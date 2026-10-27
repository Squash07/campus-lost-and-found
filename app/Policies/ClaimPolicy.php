<?php

namespace App\Policies;

use App\Models\Claim;
use App\Models\User;

class ClaimPolicy
{
    /**
     * Determine whether the user can review (approve/reject) the claim.
     * Only the poster of the item can review claims on it.
     */
    public function review(User $user, Claim $claim): bool
    {
        return $user->id === $claim->item->user_id;
    }
}
