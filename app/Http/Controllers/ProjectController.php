<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

use App\Models\User;
use App\Models\Project;

class ProjectController extends Controller {

    public function __construct() {
        $this->middleware('auth');
    }

    public function showProjectByID(Request $request, $id) {
        $project = Project::findOrFail($id);

        $this->authorize('view', $project);

        return $request->wantsJson()
            ? new JsonResponse($project->toArray(), 200)
            : view('pages.project.board', ['project' => $project]);
    }

    public function search(Request $request) {

        $searchTerm = $request->query('q') ?? '';

        // $this->authorize('search');

        $projects = $this->searchProjects($searchTerm)->appends($request->query());

        return view('pages.search.projects', ['projects' => $projects]);
    }

    public function searchProjects(string $searchTerm) {   
        return Auth::user()->projects()
            ->whereRaw('(fts_search @@ plainto_tsquery(\'english\', ?) OR project.name = ?)', [$searchTerm, $searchTerm])
            ->orderByRaw('ts_rank(fts_search, plainto_tsquery(\'english\', ?)) DESC', [$searchTerm])
            ->paginate(10);
    }

    public function showProjectCreationPage() {
        return view('pages.project.new');
    }

    public function createProject(Request $request) {
        $requestData = $request->all();

        $this->projectCreationValidator($requestData)->validate();

        $project = $this->create($requestData);

        return $request->wantsJson()
            ? new JsonResponse($project->toArray(), 200)
            : redirect()->route('project', ['id' => $project->id]);
    }

    /**
     * Get a validator for an incoming project creation request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function projectCreationValidator(array $data) {
        return Validator::make($data, [
            'name' => 'required|string|min:6|max:255',
            'description' => 'string|min:6|max:512',
        ]);
    }

    /**
     * Shows user's projects.
     *
     * @return Response
     */
    public function listUserProjects() {
        // $this->authorize('list', Project::class);
        $projects = Auth::user()->projects()->paginate(10);
        return view('pages.project.list', ['projects' => $projects]);
    }

    public function showAddUserPage($id) {
        $project = Project::findOrFail($id);

        return view('pages.project.add', ['project' => $project]);
    }

    public function addUser(Request $request, $id) {
        $requestData = $request->all();

        try {
            $user = User::where('email', $requestData['email'])->first();
            $project = Project::findOrFail($id);
            $project->users()->save($user);

        } finally {
            return redirect()->route('project', ['id' => $id]);
        }
    }

    /**
     * Creates a new project.
     *
     * @return Project The project created.
     */
    public function create(array $data) {

        $project = new Project();

        $project->name = $data['name'];
        $project->archived = FALSE;
        $project->description = $data['description'];
        $project->coordinator = Auth::user()->id;
        $project->save();

        return $project;
    }

    public function delete($id) {
        $project = Project::findOrFail($id);

        $this->authorize('delete', $project);
        $project->delete();

        return new JsonResponse($project->toArray(), 200);
    }
}