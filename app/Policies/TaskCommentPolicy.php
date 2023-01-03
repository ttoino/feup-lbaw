<?php

namespace App\Policies;

use App\Models\TaskComment;
use App\Models\User;
use App\Models\Task;
use Illuminate\Auth\Access\HandlesAuthorization;

class TaskCommentPolicy {
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(User $user, Task $task) {

        if ($user->blocked)
            $this->deny('Your user account has been blocked');  

        if ($user->is_admin)
            return $this->allow();

        if (!$task->project->users->contains($user))
            return $this->deny('You need to be a member of the task\'s project in order to see its comments');

        return $this->allow();
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\TaskComment  $TaskComment
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, TaskComment $taskComment) {

        if ($user->blocked)
            $this->deny('Your user account has been blocked');  
        
        if ($user->is_admin)
            return $this->allow();
        
        if (!$taskComment->task->project->users->contains($user))
            return $this->deny('You must be a member of this comment\'s task\'s project to be able to see it');

        return $this->allow();
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user, Task $task) {

        if ($user->blocked)
            $this->deny('Your user account has been blocked');  

        if ($user->is_admin)
            return $this->deny('Admins cannot create task comments');

        if (!$task->project->users->contains($user))
            return $this->deny('You must belong to task\'s project in order to create comments on this task');

        return $this->allow();
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\TaskComment  $TaskComment
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, TaskComment $taskComment) {

        if ($user->blocked)
            $this->deny('Your user account has been blocked');  

        if ($user->is_admin)
            return $this->deny('Admins cannot update task comments');

        if ($taskComment->author->id !== $user->id)
            return $this->deny('You need to be this comment\'s author in order to update it');

        return $this->allow();
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\TaskComment  $TaskComment
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, TaskComment $taskComment) {

        if ($user->blocked)
            $this->deny('Your user account has been blocked');  

        if ($user->is_admin)
            return $this->deny('Admins cannot delete task comments');

        if ($taskComment->author->id !== $user->id)
            return $this->deny('You need to be this comment\'s author in order to delete it');

        return $this->allow();
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\TaskComment  $TaskComment
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, TaskComment $TaskComment) {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\TaskComment  $TaskComment
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, TaskComment $TaskComment) {
        //
    }
}