<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;

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

    public function userCreationValidator(array $data) {
        return Validator::make($data, [
            'name' => 'required|string|min:6|max:255',
            'email' => 'required|string|email|unique:user_profile',
            'password' => [
              'required', 
              'confirmed', 
              Password::min(8)
                ->letters()
            ]
          ]);
    }

    public function showUserReports(Request $request, $id){
        $user = User::findOrFail($id);
        $reports = $user->reports()->paginate(10);

        return view('pages.admin.reports.user', ['user' => $user, 'reports' => $reports]);
    }

    public function showProjectReports(Request $request, $id){
        $project = Project::findOrFail($id);
        $reports = $project->reports()->paginate(10);

        return view('pages.admin.reports.project', ['projects' => $project, 'reports' => $reports]);
    }
}