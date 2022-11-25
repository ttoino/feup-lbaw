<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller {
    public function listUsers() {
        $users = User::withCount('reports')->paginate(10);

        return view('pages.admin.users', ['users' => $users]);
    }

    public function listProjects() {
        $projects = Project::withCount('reports')->paginate(10);

        return view('pages.admin.projects', ['projects' => $projects]);
    }

    public function showCreateUser(){
        return view('pages.admin.create.user');
    }

    public function createUser(Request $request){
        User::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => bcrypt($request->input('password')),
        ]);

        return redirect()->route('admin.users');
    }
}