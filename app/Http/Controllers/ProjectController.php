<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Project;
use App\Models\Report;
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
        $user = $request->user();

        $this->authorize('leaveProject', $project);

        $project->users()->detach($user);

        return $request->wantsJson()
            ? response()->json($project)
            : redirect()->route('project.list');
    }

    public function joinProject(Request $request, Project $project) {

        $this->authorize('joinProject', $project);

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

    public function setCoordinator(Request $request, Project $project) {
        $requestData = $request->all();

        $user = User::findOrFail($requestData['user']);

        $this->authorize('setCoordinator', [$project, $user]);

        $project->coordinator_id = $user;
        $project->save();
        $project = $project->fresh();

        return $request->wantsJson()
            ? response()->json($project)
            : redirect()->route('project.members', ['project' => $project]);
    }

    public function searchProjects(Request $request, string $searchTerm) {

        $userProjects = $request->user()->projects();

        if (!empty($searchTerm))
            $userProjects = $userProjects->whereRaw('(fts_search @@ plainto_tsquery(\'english\', ?) OR project.name = ?)', [$searchTerm, $searchTerm])
                ->orderByRaw('ts_rank(fts_search, plainto_tsquery(\'english\', ?)) DESC', [$searchTerm]);

        return $userProjects->paginate(10);
    }

    public function create() {

        $this->authorize('create', Project::class);

        return view('pages.project.new');
    }

    public function store(Request $request) {
        $this->projectCreationValidator($request)->validate();

        $this->authorize('create', Project::class);

        $project = $this->createProject($request);

        return $request->wantsJson()
            ? response()->json($project, 201)
            : redirect()->route('project', ['project' => $project]);
    }

    /**
     * Creates a new project.
     *
     * @return Project The project created.
     */
    public function createProject(Request $request) {

        $project = new Project();
        $data = $request->all();

        $project->name = $data['name'];
        $project->archived = FALSE;
        $project->description = $data['description'];
        $project->coordinator_id = $request->user()->id;
        $project->save();

        return $project;
    }

    /**
     * Get a validator for an incoming project creation request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function projectCreationValidator(Request $request) {
        return Validator::make($request->all(), [
            'name' => 'required|string|min:6|max:255',
            'description' => 'string|min:6|max:512',
        ]);
    }


    public function update(Request $request, Project $project) {
        $this->projectUpdateValidator($request)->validate();

        // this is different than 'edit' in that only the project's coordinator can update the project's attributes
        $this->authorize('update', $project);

        $project = $this->updateProject($project, $request);

        return $request->wantsJson()
            ? response()->json($project)
            : redirect()->route('project', ['project' => $project]);
    }

    /**
     * Creates a new project.
     *
     * @return Project The project created.
     */
    public function updateProject(Project $project, Request $request) {

        $data = $request->all();

        if (($data['name'] ??= null) !== null)
            $project->name = $data['name'];

        if (($data['archived'] ??= null) !== null)
            $project->archived = $data['archived'];

        if (($data['description'] ??= null) !== null)
            $project->description = $data['description'];

        if (($data['coordinator_id'] ??= null) !== null)
            $project->coordinator_id = $data['coordinator_id'];

        if ($project->isDirty())
            $project->last_modification_date = now();

        $project->save();

        return $project;
    }

    /**
     * Get a validator for an incoming project creation request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function projectUpdateValidator(Request $request) {
        return Validator::make($request->all(), [
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
    public function index(Request $request) {

        $this->authorize('viewAny', [Project::class]);

        $searchTerm = $request->query('q') ?? '';

        $projects = $this->searchProjects($request, $searchTerm)->withQueryString();

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

        return request()->wantsJson()
            ? response()->json()
            : redirect()->route('project', ['project' => $project]);
    }

    public function toggleFavorite(Request $request, Project $project) {

        $this->authorize('toggleFavorite', $project);

        $member = $project->users()->get()->first(fn(User $user) => $user->id === $request->user()->id);

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

        return $request->wantsJson()
            ? response()->json($members)
            : response()->view('pages.project.members', ['project' => $project, 'members' => $members]);
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

    public function getProjectTasks(Request $request, Project $project) {

        $this->authorize('getProjectTasks', $project);

        $searchTerm = $request->query('q') ?? '';

        $tasks = $this->searchTasks($searchTerm, $project)->withQueryString();

        return $request->wantsJson()
            ? response()->json($tasks)
            : response()->view('pages.project.tasks', ['tasks' => $tasks]);
    }

    public function searchTasks(string $searchTerm, Project $project) {

        $projectTasks = $project->tasks();

        if (!empty($searchTerm))
            $projectTasks = $projectTasks->whereRaw('(task.fts_search @@ plainto_tsquery(\'english\', ?) OR task.name = ?)', [$searchTerm, $searchTerm])
                ->orderByRaw('ts_rank(task.fts_search, plainto_tsquery(\'english\', ?)) DESC', [$searchTerm]);

        return $projectTasks->cursorPaginate(10);
    }

    public function getProjectTags(Request $request, Project $project) {
        $this->authorize('getProjectTags', $project);

        $searchTerm = $request->query('q') ?? '';

        $tags = $this->searchTags($project, $searchTerm)->withQueryString();

        return $request->wantsJson()
            ? response()->json($tags)
            : response()->view('pages.project.tags', ['tags' => $tags]);
    }

    public function searchTags(Project $project, string $search) {
        $members = $project->tags();

        if (!empty($search)) {
            $members = $members->where('title', 'like', '%' . ProjectController::escape_like($search) . '%');
        }

        return $members->cursorPaginate(10);
    }

    public function showReportForm(Project $project) {
        $this->authorize('report', $project);

        return view('pages.reportproject', ['project' => $project]);
    }

    public function report(Request $request, Project $project) {
        
        $this->reportValidator($request);

        $requestData = $request->all();

        $this->authorize('report', $project);

        $report = new Report();

        $report->reason = $requestData['reason'];
        $report->project_id = $project->id;
        $report->creator_id = $request->user()->id;
        $report->save();

        return redirect()->route('project', ['project' => $project]);
    }

    protected function reportValidator(Request $request) {
        return Validator::make($request->all(), [
            'reason' => 'string|min:6|max:512'
        ]);
    }

}