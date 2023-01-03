<?php

namespace App\Policies;

use App\Models\Project;
use App\Models\User;
use App\Models\Notification;
use Illuminate\Auth\Access\HandlesAuthorization;

class ProjectPolicy {
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

        if ($user->is_admin)
            return $this->deny('Admins cannot update projects');

        if ($project->archived)
            return $this->deny('Cannot update an archived project');

        if (!$project->users->contains($user))
            return $this->deny('Only the project\'s members can edit this project');

        return $this->allow();
    }

    public function update(User $user, Project $project) {

        if ($user->is_admin)
            return $this->deny('Admins cannot update projects');

        if ($project->archived)
            return $this->deny('Cannot update an archived project');

        if ($project->coordinator_id !== $user->id)
            return $this->deny('Only the project\'s coordinator can edit this project');

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
            return $this->deny('Only admins or the project\'s coordinator can delete this project');

        return $this->allow();
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Project  $project
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, Project $project) {
        //
    }

    public function toggleFavorite(User $user, Project $project) {

        if ($user->is_admin)
            return $this->deny('Admins cannot mark projects as favorites');

        if (!$project->users->contains($user))
            return $this->deny('Only the project\'s members can mark or unmark this project as favorite');

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

        if ($model->id === $project->coordinator_id)
            return $this->deny('A project\'s coordinator cannot remove themselves');

        if ($project->archived)
            return $this->deny('Cannot remove users from an archived project');

        if (!$project->users->contains($model))
            return $this->deny('Cannot remove user from a project they are not a member of');

        return $this->allow();
    }

    public function leaveProject(User $user, Project $project) {

        if ($user->is_admin)
            return $this->deny('Admins cannot leave projects');

        if ($user->id === $project->coordinator_id)
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

    public function getProjectTags(User $user, Project $project) {

        if ($user->is_admin || $project->users->contains($user))
            return $this->allow();

        return $this->deny('Only admins or project members can see the project\'s tags');
    }

    public function getProjectTasks(User $user, Project $project) {
        if (!$user->is_admin && !$user->projects->contains($project))
            return $this->deny('Only admins or members of the given project can search tasks in it');

        return $this->allow();
    }

    public function archive(User $user, Project $project) {
        if ($project->archived)
            return $this->deny('Project is already archived');

        if ($project->coordinator_id !== $user->id)
            return $this->deny('Only the project coordinator can archive it');

        return $this->allow();
    }

    public function unarchive(User $user, Project $project) {
        if (!$project->archived)
            return $this->deny('Project is not archived');

        if ($project->coordinator_id !== $user->id)
            return $this->deny('Only the project\'s coordinator can unarchive it');

        return $this->allow();
    }

    public function report(User $user, Project $project) {

        if ($user->is_admin)
            return $this->deny('Admins cannot report projects');

        return $this->allow();
    }

    public function joinProject(User $user, Project $project) {

        if ($user->is_admin)
            return $this->deny('Admins cannot accept project invitations');

        if ($project->users->contains($user))
            return $this->deny('You are already a member of this project');

        $projectInvite = Notification::where('type', 'App\Notifications\ProjectInvite')->where('notifiable_id', $user->id);

        $invitedToProject = $projectInvite->get()->reduce(fn(bool $carry, Notification $notification) => $carry || $notification->json['project']?->id === $project->id, false);

        if ($invitedToProject)
            return $this->allow();

        return $this->deny('Cannot accept invitation for a project you were not invited for');
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Project  $project
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, Project $project) {
        //
    }
}