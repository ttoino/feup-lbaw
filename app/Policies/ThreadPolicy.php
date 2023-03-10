<?php

namespace App\Policies;

use App\Models\Thread;
use App\Models\User;
use App\Models\Project;
use Illuminate\Auth\Access\HandlesAuthorization;

class ThreadPolicy {
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(User $user) {
        //
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Thread  $thread
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, Thread $thread) {

        if ($user->blocked)
            return $this->deny('Your user account has been blocked');  

        if ($user->is_admin)
            return $this->allow();

        if ($thread->author->id !== $user->id)
            return $this->allow();

        if ($thread->project->users->contains($user))
            return $this->allow();
        
        return $this->deny('Only admins, the thread\'s author or a member of the thread\'s project can view this thread');
    }

    public function viewCreationForm(User $user, Project $project) {

        if ($user->blocked)
            return $this->deny('Your user account has been blocked');  

        if ($user->is_admin)
            return $this->allow();

        if ($project->users->contains($user))
            return $this->allow();

        return $this->deny('Only admins or a member of the given project can view this thread');
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user, Project $project) {

        if ($user->blocked)
            return $this->deny('Your user account has been blocked');  

        if ($user->is_admin)
            return $this->deny('Admins cannot create threads');

        if (!$project->users->contains($user))
            return $this->deny('To create a thread in this project you must be a member of this project');

        return $this->allow();
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Thread  $thread
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, Thread $thread) {

        if ($user->blocked)
            return $this->deny('Your user account has been blocked');  

        if ($user->is_admin)
            return $this->deny('Admins cannot edit threads');

        if ($user->id !== $thread->author_id)
            return $this->deny('To edit a thread you must be its author'); 

        return $this->allow();
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Thread  $thread
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Thread $thread) {

        if ($user->blocked)
            return $this->deny('Your user account has been blocked');  

        if ($user->is_admin)
            return $this->deny('Admins cannot delete threads');

        if ($user->id !== $thread->author_id)
            return $this->deny('To delete a thread you must be its author');     
    
        return $this->allow();
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Thread  $thread
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, Thread $thread) {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Thread  $thread
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, Thread $thread) {
        //
    }
}