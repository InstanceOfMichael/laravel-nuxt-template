<?php

namespace App\Policies;

use App\User;
use App\Questiontopic;
use Illuminate\Auth\Access\HandlesAuthorization;

class QuestiontopicPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the questiontopic.
     *
     * @param  \App\User  $user
     * @param  \App\Questiontopic  $questiontopic
     * @return mixed
     */
    public function view(User $user, Questiontopic $questiontopic)
    {
        return true;
    }

    /**
     * Determine whether the user can create questiontopics.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can update the questiontopic.
     *
     * @param  \App\User  $user
     * @param  \App\Questiontopic  $questiontopic
     * @return mixed
     */
    public function update(User $user, Questiontopic $questiontopic)
    {
        return $questiontopic->op_id === $user->id;
    }

    /**
     * Determine whether the user can delete the questiontopic.
     *
     * @param  \App\User  $user
     * @param  \App\Questiontopic  $questiontopic
     * @return mixed
     */
    public function delete(User $user, Questiontopic $questiontopic)
    {
        return false;
    }

    /**
     * Determine whether the user can restore the questiontopic.
     *
     * @param  \App\User  $user
     * @param  \App\Questiontopic  $questiontopic
     * @return mixed
     */
    public function restore(User $user, Questiontopic $questiontopic)
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the questiontopic.
     *
     * @param  \App\User  $user
     * @param  \App\Questiontopic  $questiontopic
     * @return mixed
     */
    public function forceDelete(User $user, Questiontopic $questiontopic)
    {
        return false;
    }
}
