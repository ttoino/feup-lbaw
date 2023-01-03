<?php

namespace App\Policies;

use App\Models\Tag;
use App\Models\User;
use App\Models\Project;
use Illuminate\Auth\Access\HandlesAuthorization;

class TagPolicy {
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
     * @param  \App\Models\Tag  $tag
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, Tag $tag) {

        if ($user->is_admin)
            return $this->allow();

        if ($tag->project->users->contains($user))
            return $this->allow();

        return $this->deny('Only admins or a member of the tag\'s project can view this tag');
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user, Project $project) {

        if ($user->is_admin)
            return $this->deny('Admins cannot create tags');

        if (!$project->users->contains($user))
            return $this->deny('To create a tag in this project you must be a member of this project');

        return $this->allow();
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Tag  $tag
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, Tag $tag) {
        if ($user->is_admin)
            return $this->deny('Admins cannot edit tags');

        if (!$tag->project->users->contains($user))
            return $this->deny('To edit a tag you must be a member of its project');

        return $this->allow();
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Tag  $tag
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Tag $tag) {
        if ($user->is_admin)
            return $this->deny('Admins cannot delete tags');

        if (!$tag->project->users->contains($user))
            return $this->deny('To delete a tag you must be a member of its project');

        return $this->allow();
    }
}