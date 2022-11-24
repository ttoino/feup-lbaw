<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller {
    public function listUsers() {
        $users = User::paginate(10);

        return view('pages.admin.users', ['users' => $users]);
    }

    public function listProjects() {
        $projects = Project::paginate(10);

        return view('pages.admin.projects', ['projects' => $projects]);
    }
}