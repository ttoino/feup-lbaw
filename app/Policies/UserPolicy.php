<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Project;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy {
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
     * @param  \App\Models\User  $model
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, User $model) {

        if ($user->is_admin)
            return $this->allow();

        if ($user->id === $model->id)
            return $this->allow();

        $projectsInCommon = $user->projects->intersect($model->projects)->count() > 0;

        if ($projectsInCommon)
            return $this->allow();

        return $this->deny('Only admins, the profile\'s owner or users that share a project with the profile\'s owner can view this profile');
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user) {
        //
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\User  $model
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, User $model) {

        if ($user->is_admin)
            return $this->allow();

        if ($user->id === $model->id)
            return $this->allow();

        return $this->deny('Only admins or the profile\'s owner can update this user profile');
    }

    public function showProfileEditPage(User $user, User $model) {
        if ($user->is_admin)
            return $this->allow();

        if ($user->id === $model->id)
            return $this->allow();

        return $this->deny('Only admins or the profile\'s owner can update this user profile');
    }

    public function block(User $user, User $model) {
        if (!$user->is_admin)
            return $this->deny('Only admins can perform this action');
        
        if ($model->is_admin)
            return $this->deny('This action can\'t be performed on admins');
        
        if ($model->blocked)
            return $this->deny('User is already blocked');
        
        return $this->allow();
    }

    public function unblock(User $user, User $model) {
        if (!$user->is_admin)
            return $this->deny('Only admins can perform this action');

        if ($model->is_admin)
            return $this->deny('This action can\'t be performed on admins');

        if (!$model->blocked)
            return $this->deny('User is not blocked');

        return $this->allow();
    }


    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\User  $model
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, User $model) {

        if ($model->is_admin)
            return $this->deny('Cannot delete admin accounts');

        if (!$user->is_admin && $user->id !== $model->id)
            return $this->deny('Only admins can delete accounts that belong to other users');
        
        $userProjects = Project::where('coordinator_id', $model->id);

        $projectsHaveOtherMembers = $userProjects->get()->reduce(fn(bool $carry, Project $project) => $carry | ($project->users->count() > 1), false);

        if ($userProjects->count() > 0 && $projectsHaveOtherMembers)
            return $this->deny('Cannot delete coordinator account while new coordinators are not assigned for the user\'s projects');

        return $this->allow();
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\User  $model
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, User $model) {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\User  $model
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, User $model) {
        //
    }
}