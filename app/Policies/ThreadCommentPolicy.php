<?php

namespace App\Policies;

use App\Models\ThreadComment;
use App\Models\User;
use App\Models\Thread;
use Illuminate\Auth\Access\HandlesAuthorization;

class ThreadCommentPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(User $user)
    {
        //
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ThreadComment  $threadComment
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, ThreadComment $threadComment) {
        if (!$threadComment->thread->project->users->contains($user))
            return $this->deny('You must be a member of this comment\'s thread\'s project to be able to see it');
    
        return $this->allow();
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user, Thread $thread) {
        if ($user->is_admin)
            return $this->deny('Admins cannot create thread comments');

        if (!$thread->project->users->contains($user))
            return $this->deny('You must belong to thread\'s project in order to create comments on this thread');

        return $this->allow();
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ThreadComment  $threadComment
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, ThreadComment $threadComment) {
        if ($user->is_admin)
            return $this->deny('Admins cannot update thread comments');

        if ($threadComment->author->id !== $user->id)
            return $this->deny('You need to be this comment\'s author in order to update it');

        return $this->allow();
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ThreadComment  $threadComment
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, ThreadComment $threadComment) {
        if ($user->is_admin)
            return $this->deny('Admins cannot delete thread comments');

        if ($threadComment->author->id !== $user->id)
            return $this->deny('You need to be this comment\'s author in order to delete it');
    
        return $this->allow();
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ThreadComment  $threadComment
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, ThreadComment $threadComment)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ThreadComment  $threadComment
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, ThreadComment $threadComment)
    {
        //
    }
}
