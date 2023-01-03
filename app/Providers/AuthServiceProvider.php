<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Illuminate\Auth\Access\Response;
use App\Models\User;

class AuthServiceProvider extends ServiceProvider {
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        \App\Models\Project::class => \App\Policies\ProjectPolicy::class,
        \App\Models\Task::class => \App\Policies\TaskPolicy::class,
        \App\Models\TaskGroup::class => \App\Policies\TaskGroupPolicy::class,
        \App\Models\User::class => \App\Policies\UserPolicy::class,
        \App\Models\Thread::class => \App\Policies\ThreadPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot() {
        $this->registerPolicies();

        Gate::define('admin-action', function (User $user) {
            if (!$user->is_admin)
                return Response::deny('Only an admin can perform this action');

            return Response::allow();
        });
    }
}