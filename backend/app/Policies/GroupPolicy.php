<?php

namespace App\Policies;

use App\Models\Group;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class GroupPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Group  $group
     * @return mixed
     */
    public function delete(User $user, Group $group)
    {
        return $group->user_id === $user->id;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Group  $group
     * @return mixed
     */
    public function update(User $user, Group $group)
    {
        $changes = $group->getDirty();

        if (isset($changes['user_id'])) {
            $userid = $group->getOriginal('user_id');
            return $user->id == $userid;
        }
        return true;
    }

    /**
     * Determine if the logged user can update the group state
     */
    public function canChangeMember(User $user, Group $group)
    {
        return $group->usersAccepted->contains($user);
    }
}
