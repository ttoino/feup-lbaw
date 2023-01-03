<?php

namespace App\Policies;

use App\Models\Task;
use App\Models\User;
use App\Models\TaskGroup;
use App\Models\Project;
use Illuminate\Auth\Access\HandlesAuthorization;

class TaskPolicy {
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
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, Task $task, Project $project) {

        if ($user->blocked)
            $this->deny('Your user account has been blocked');  

        if ($project->id !== $task->project->id)
            return $this->deny('Task is not a child of the given project');

        if (!$user->is_admin && !$task->project->users->contains($user))
            return $this->deny('Only admins or members of this task\'s project can view this task');

        return $this->allow();
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user, TaskGroup $task_group) {

        if ($user->blocked)
            $this->deny('Your user account has been blocked');  

        if ($user->is_admin)
            return $this->deny('Admins cannot create tasks');

        if (!$task_group->project->users->contains($user))
            return $this->deny('Only members of the given group\'s project can create tasks');

        return $this->allow();
    }

    /**
     * Determine whether the user can edit the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function edit(User $user, Task $task) {

        if ($user->blocked)
            $this->deny('Your user account has been blocked');  

        if ($user->is_admin)
            return $this->deny('Admins cannot edit tasks');

        if (!$task->project->users->contains($user))
            return $this->deny('Only members of the given task\'s project can update tasks');


        return $this->allow();
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Task $task) {

        if ($user->blocked)
            $this->deny('Your user account has been blocked');  

        if ($user->is_admin)
            return $this->deny('Admins cannot delete tasks');

        if (!$task->project->users->contains($user))
            return $this->deny('Only members of the given task\'s project can delete tasks');

        return $this->allow();
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, Task $task) {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, Task $task) {
        //
    }

    /**
     * Determine if the user is allowed to mark this task as completed.
     *  
     * @param  \App\Models\User  $user
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function completeTask(User $user, Task $task) {

        if ($user->blocked)
            $this->deny('Your user account has been blocked');  

        if ($user->is_admin)
            return $this->deny('Admins cannot mark tasks as completed');

        if (!$task->project->users->contains($user))
            return $this->deny('Only members of the task\'s project can mark it as completed');

        return $this->allow();
    }

    public function incompleteTask(User $user, Task $task) {

        if ($user->blocked)
            $this->deny('Your user account has been blocked');  

        if ($user->is_admin)
            return $this->deny('Admins cannot mark tasks as incomplete');

        if (!$task->project->users->contains($user))
            return $this->deny('Only members of the task\'s project can mark it as incomplete');  

        return $this->allow();
    }

    public function search(User $user, Project $project) {

        if ($user->blocked)
            $this->deny('Your user account has been blocked');  

        if (!$user->is_admin && !$user->projects->contains($project))
            return $this->deny('Only admins or members of the given project can search tasks in it');   

        return $this->allow();
    }
}