<?php

namespace App\Policies;

use App\Models\Project;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class ProjectPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(User $user) {
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Project  $project
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, Project $project) {
        if (!$user->is_admin && !$project->users->contains($user))
            return $this->deny('Only admins or the project\'s members can view this project');
        
        return $this->allow();
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user) {

        if ($user->is_admin)
            return $this->deny('Admins cannot create projects');

        return $this->allow();
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Project  $project
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function edit(User $user, Project $project) {

        if ($project->archived)
            return $this->deny('Cannot update an archived project');

        if (!$project->users->contains($user))
            return $this->deny('Only the project\'s members can edit this project');
        
        return $this->allow();
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Project  $project
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Project $project) {
        if (!$user->is_admin && $project->coordinator_id !== $user->id)
            return $this->deny('Only admins the project\'s coordinator can delete this project');
        
        return $this->allow();
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Project  $project
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, Project $project)
    {
        //
    }

    public function toggleFavorite(User $user, Project $project) {

        if ($user->is_admin)
            return $this->deny('Admins cannot mark projects as favorites');

        if (!$project->users->contains($user))
            return $this->deny('Only the project\'s members can mark this project as favorite');
        
        return $this->allow();
    }

    public function showAddUserPage(User $user, Project $project) {
        if ($user->id !== $project->coordinator_id)
            return $this->deny('Only the project\'s coordinator can see the \'Add User to Project\' page');
        
        return $this->allow();
    }

    public function addUser(User $user, Project $project, User $model) {

        if ($user->id !== $project->coordinator_id)
            return $this->deny('Only the project\'s coordinator can invite users to this project');

        if ($project->archived)
            return $this->deny('Cannot invite users to an archived project');

        if ($project->users->contains($model))
            return $this->deny('Cannot invite user to a project they are already a member of');

        return $this->allow();
    }

    public function removeUser(User $user, Project $project, User $model) {

        if ($user->id !== $project->coordinator_id)
            return $this->deny('Only the project\'s coordinator can remove users from this project');

        if ($project->archived)
            return $this->deny('Cannot remove users from an archived project');

        if (!$project->users->contains($model))
            return $this->deny('Cannot remove user from a project they are not a member of');

        return $this->allow();
    }

    public function leaveProject(User $user, Project $project) {

        if ($user->is_admin)
            return $this->deny('Admins cannot leave projects');

        if ($user->id !== $project->coordinator_id)
            return $this->deny('The project\' coordinator cannot leave the project without appointing a new coordinator');

        if (!$project->users->contains($user))
            return $this->deny('Only the project\'s members can leave the project');
        
        return $this->allow();
    }

    public function getProjectMembers(User $user, Project $project) {

        if ($user->is_admin || $project->users->contains($user))
            return $this->allow();

        return $this->deny('Only admins or project members can see the project\'s members');
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Project  $project
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, Project $project)
    {
        //
    }
}
