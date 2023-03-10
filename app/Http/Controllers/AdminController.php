<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;

class AdminController extends Controller {
    public function listUsers(Request $request) {

        Gate::authorize('admin-action');

        $searchTerm = $request->query('q') ?? '';
        
        $users = $this->searchUsers($searchTerm)->withQueryString();

        return $request->expectsJson()
            ? response()->json($users)
            : response()->view('pages.admin.users', ['users' => $users]);
    }

    public function listProjects(Request $request) {
        
        Gate::authorize('admin-action');

        $searchTerm = $request->query('q') ?? '';

        $projects = $this->searchProjects($searchTerm)->withQueryString();

        return $request->expectsJson()
            ? response()->json($projects)
            : response()->view('pages.admin.projects', ['projects' => $projects]);
    }

    public function showCreateUser(){

        Gate::authorize('admin-action');

        return response()->view('pages.admin.create.user');
    }

    public function createUser(Request $request){
        Gate::authorize('admin-action');
        
        $this->userCreationValidator($request)->validate();

        User::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => bcrypt($request->input('password')),
        ]);

        return redirect()->route('admin.users');
    }

    public function userCreationValidator(Request $request) {
        return Validator::make($request->all(), [
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

    public function showUserReports(Request $request, User $user){
        
        Gate::authorize('admin-action');

        $reports = $user->reports()->cursorPaginate(10);

        return response()->view('pages.admin.reports.user', ['user' => $user, 'reports' => $reports]);
    }

    public function showProjectReports(Request $request, Project $project){

        Gate::authorize('admin-action');

        $reports = $project->reports()->cursorPaginate(10);

        return response()->view('pages.admin.reports.project', ['projects' => $project, 'reports' => $reports]);
    }

    public function searchUsers(string $search) {

        $users = User::withCount('reports');

        if (!empty($search))
            $users = $users->where('name', 'like', '%'.AdminController::escape_like($search).'%'); 
        
        return $users->cursorPaginate(10);
    }

    public function searchProjects(string $search) {

        $projects = Project::with('reports');

        if (!empty($search))
            $projects = $projects->whereRaw('(fts_search @@ plainto_tsquery(\'english\', ?) OR project.name = ?)', [$search, $search])
                ->orderByRaw('ts_rank(fts_search, plainto_tsquery(\'english\', ?)) DESC', [$search]); 
        
        return $projects->cursorPaginate(10);
    }

    // this should be moved to another place but meh
    protected function escape_like(string $value, string $char = '\\') {
        return str_replace(
            [$char, '%', '_'],
            [$char.$char, $char.'%', $char.'_'],
            $value
        );
    }
}