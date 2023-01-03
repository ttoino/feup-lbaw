<?php

namespace App\Policies;

use App\Models\TaskGroup;
use App\Models\User;
use App\Models\Project;
use Illuminate\Auth\Access\HandlesAuthorization;

class TaskGroupPolicy
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
     * @param  \App\Models\TaskGroup  $taskGroup
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, TaskGroup $taskGroup) {

        if ($user->blocked)
            $this->deny('Your user account has been blocked');  
            
        if (!$user->is_admin && !$taskGroup->project->users->contains($user))
            return $this->deny('Only admins or members of this group\'s project can view info on this task group');

        return $this->allow();
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user, Project $project) {
        
        if ($user->blocked)
            $this->deny('Your user account has been blocked');  

        if ($user->is_admin)
            return $this->deny('Admins cannot create task groups');

        if (!$project->users->contains($user))
            return $this->deny('Only members of this given project can create task groups');
        
        return $this->allow();
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\TaskGroup  $taskGroup
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, TaskGroup $taskGroup) {

        if ($user->blocked)
            $this->deny('Your user account has been blocked');  

        if ($user->is_admin)
            return $this->deny('Admins cannot update task groups');
            
        if (!$taskGroup->project->users->contains($user))
            return $this->deny('Only members of this group\'s project can update task groups');
        
        return $this->allow();
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\TaskGroup  $taskGroup
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, TaskGroup $taskGroup) {

        if ($user->blocked)
            $this->deny('Your user account has been blocked');  

        if ($user->is_admin)
            return $this->deny('Admins cannot delete task groups');

        if (!$taskGroup->project->users->contains($user))
            return $this->deny('Only members of this group\'s project can update task groups');
        
        return $this->allow();
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\TaskGroup  $taskGroup
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, TaskGroup $taskGroup)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\TaskGroup  $taskGroup
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, TaskGroup $taskGroup)
    {
        //
    }
}
