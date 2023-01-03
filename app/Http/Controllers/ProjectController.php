<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Project;
use App\Notifications\ProjectInvite;
use App\Notifications\ProjectRemoved;
use App\Notifications\ProjectArchived;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Validator;

class ProjectController extends Controller {

    public function show(Project $project) {
        $this->authorize('view', $project);

        return response()->json($project->toArray());
    }

    public function showProjectBoard(Request $request, Project $project) {

        $this->authorize('view', $project);

        return $request->wantsJson()
            ? response()->json($project)
            : response()->view('pages.project.board', ['project' => $project]);
    }

    public function showProjectInfo(Request $request, Project $project) {
        $this->authorize('view', $project);

        return $request->wantsJson()
            ? response()->json($project)
            : response()->view('pages.project.info', ['project' => $project]);
    }

    public function showProjectTimeline(Request $request, Project $project) {
        $this->authorize('view', $project);

        return $request->wantsJson()
            ? response()->json($project)
            : response()->view('pages.project.tbd', ['project' => $project]);
    }

    public function showProjectForum(Request $request, Project $project) {
        $this->authorize('view', $project);

        return $request->wantsJson()
            ? response()->json($project)
            : response()->view('pages.project.forum', ['project' => $project]);
    }

    public function leaveProject(Request $request, Project $project) {
        $user = Auth::user();

        $this->authorize('leaveProject', $project);

        $project->users()->detach($user);
        return $request->wantsJson()
            ? response()->json($project)
            : redirect()->route('project.list');
    }

    public function joinProject(Request $request, Project $project) {

        // TODO: handle authorization with policies

        $user = User::findOrFail($request->query('user'));

        $project->users()->save($user);

        return redirect()->route('project', ['project' => $project]);
    }

    public function removeUser(Request $request, Project $project, User $user) {

        $this->authorize('removeUser', [$project, $user]);

        $project->users()->detach($user);

        $user->notify(new ProjectRemoved($project));

        return $request->wantsJson()
            ? response()->json($project)
            : redirect()->route('project.list');
    }

    public function search(Request $request) {
        $searchTerm = $request->query('q') ?? '';

        $projects = $this->searchProjects($searchTerm)->withQueryString();

        return response()->view('pages.search.projects', ['projects' => $projects]);
    }

    public function searchProjects(string $searchTerm) {

        $userProjects = Auth::user()->projects();

        if (!empty($searchTerm))
            $userProjects = $userProjects->whereRaw('(fts_search @@ plainto_tsquery(\'english\', ?) OR project.name = ?)', [$searchTerm, $searchTerm])
                ->orderByRaw('ts_rank(fts_search, plainto_tsquery(\'english\', ?)) DESC', [$searchTerm]);

        return $userProjects->cursorPaginate(10);
    }

    public function create() {
        return view('pages.project.new');
    }

    public function store(Request $request) {
        $requestData = $request->all();

        $this->projectCreationValidator($requestData)->validate();

        $this->authorize('create', Project::class);

        $project = $this->createProject($requestData);

        return $request->wantsJson()
            ? response()->json($project, 201)
            : redirect()->route('project', ['project' => $project]);
    }

    /**
     * Creates a new project.
     *
     * @return Project The project created.
     */
    public function createProject(array $data) {

        $project = new Project();

        $project->name = $data['name'];
        $project->archived = FALSE;
        $project->description = $data['description'];
        $project->coordinator_id = Auth::user()->id;
        $project->save();

        return $project;
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


    public function update(Request $request, Project $project) {
        $requestData = $request->all();

        $this->projectUpdateValidator($requestData)->validate();

        $this->authorize('update', $project);

        $project = $this->updateProject($project, $requestData);

        return $request->wantsJson()
            ? response()->json($project)
            : redirect()->route('project', ['project' => $project]);
    }

    /**
     * Creates a new project.
     *
     * @return Project The project created.
     */
    public function updateProject(Project $project, array $data) {

        if (($data['name'] ??= null) !== null)
            $project->name = $data['name'];

        if (($data['archived'] ??= null) !== null)
            $project->archived = $data['archived'];

        if (($data['description'] ??= null) !== null)
            $project->description = $data['description'];

        if (($data['coordinator_id'] ??= null) !== null)
            $project->coordinator_id = $data['coordinator_id'];

        $project->save();

        return $project;
    }

    /**
     * Get a validator for an incoming project creation request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function projectUpdateValidator(array $data) {
        return Validator::make($data, [
            'name' => 'string|min:6|max:255',
            'description' => 'string|min:6|max:512',
            'coordinator_id' => 'integer',
            'archived' => 'boolean'
        ]);
    }

    /**
     * Shows user's projects.
     *
     * @return Response
     */
    public function index() {
        $projects = Auth::user()->projects()->cursorPaginate(10);

        return response()->view('pages.project.list', ['projects' => $projects]);
    }

    public function showInviteUserPage(Project $project) {

        $this->authorize('showAddUserPage', $project);

        return response()->view('pages.project.add', ['project' => $project]);
    }

    public function inviteUser(Request $request, Project $project) {

        $user = User::where('email', $request->input('email'))->first();

        $this->authorize('addUser', [$project, $user]);

        $url = URL::signedRoute('project.join', ['project' => $project, 'user' => $user]);

        $user->notify(new ProjectInvite($url, $project));

        return redirect()->route('project', ['project' => $project]);
    }

    public function toggleFavorite(Request $request, Project $project) {

        // handle auth
        $this->authorize('toggleFavorite', $project);

        $member = $project->users()->get()->first(fn(User $user) => $user->id === Auth::user()->id);

        $member->pivot->is_favorite = !$member->pivot->is_favorite;
        $member->pivot->save();

        return response()->json(['isFavorite' => $member->pivot->is_favorite]);
    }

    public function archive(Request $request, Project $project) {
        $this->authorize('archive', $project);

        $project->archived = true;
        $project->save();

        foreach ($project->users as $user) {
            $user->notify(new ProjectArchived($project));
        }

        return $request->wantsJson()
            ? response()->json($project)
            : redirect()->route('project.info', ['project' => $project]);
    }

    public function unarchive(Request $request, Project $project) {
        $this->authorize('unarchive', $project);

        $project->archived = false;
        $project->save();

        return $request->wantsJson()
            ? response()->json($project)
            : redirect()->route('project.info', ['project' => $project]);
    }

    public function destroy(Request $request, Project $project) {

        $this->authorize('delete', $project);
        $project->delete();

        return $request->wantsJson()
            ? response()->json($project)
            : redirect()->route('project.list');
    }

    public function getProjectMembers(Request $request, Project $project) {

        $this->authorize('getProjectMembers', $project);

        $searchTerm = $request->query('q') ?? '';

        $members = $this->searchMembers($project, $searchTerm)->withQueryString();

        return response()->json($members);
    }

    public function searchMembers(Project $project, string $search) {
        $members = $project->users();

        if (!empty($search)) {
            $members = $members->where('name', 'like', '%' . ProjectController::escape_like($search) . '%');
        }

        return $members->cursorPaginate(10);
    }

    protected function escape_like(string $value, string $char = '\\') {
        return str_replace(
            [$char, '%', '_'],
            [$char . $char, $char . '%', $char . '_'],
            $value
        );
    }

    public function getProjectTags(Request $request, Project $project) {
        $this->authorize('getProjectTags', $project);

        $tags = $project->tags()->cursorPaginate(10);

        return response()->json($tags);
    }

    public function report(Project $project) {
        $this->authorize('report', $project);

        return view('pages.reportproject', ['project' => $project]);
    }

}