<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Models\Project;
use App\Models\ProjectInvitation;
use App\Models\ProjectTimelineAction;
use App\Models\Report;
use App\Models\Tag;
use App\Models\Task;
use App\Models\TaskComment;
use App\Models\TaskGroup;
use App\Models\Thread;
use App\Models\ThreadComment;
use App\Models\User;

class DebugController extends Controller {
    public function dump() {
        echo '<pre>';
        echo 'notifications: ';
        print_r(Notification::all()->toArray());
        echo 'projects: ';
        print_r(Project::with('tasks', 'taskGroups')->get()->toArray());
        echo 'invitations: ';
        print_r(ProjectInvitation::with('creator', 'project')->get()->toArray());
        echo 'timeline: ';
        print_r(ProjectTimelineAction::with('project')->get()->toArray());
        echo 'reports: ';
        print_r(Report::with('creator', 'user', 'project')->get()->toArray());
        echo 'tags: ';
        print_r(Tag::with('project')->get()->toArray());
        echo 'tasks: ';
        print_r(Task::with('taskGroup', 'comments')->get()->toArray());
        echo 'comments: ';
        print_r(TaskComment::with('task')->get()->toArray());
        echo 'groups: ';
        print_r(TaskGroup::with('tasks')->get()->toArray());
        echo 'threads: ';
        print_r(Thread::with('project', 'comments')->get()->toArray());
        echo 'comments: ';
        print_r(ThreadComment::with('thread')->get()->toArray());
        echo 'users: ';
        print_r(User::with('projects')->get()->toArray());
        echo '</pre>';
    }
}