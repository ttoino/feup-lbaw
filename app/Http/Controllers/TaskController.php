<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\TaskGroup;
use App\Models\Project;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TaskController extends Controller {

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function createTask(Request $request) {
        $requestData = $request->all();

        $this->taskCreationValidator($requestData)->validate();

        //$this->authorize('create', $task);

        $task = $this->create($requestData);

        $projectId = TaskGroup::findOrFail($task->task_group)->project;

        return $request->wantsJson()
            ? new JsonResponse([$task], 201)
            : redirect()->route('project', ['id' => $projectId]);
    }

    public function create(array $data) {

        $task = new Task();

        $task->name = $data['name'];
        $task->description = $data['description'] ?? '';
        $task->task_group = $data['task_group'];
        $task->position = (Task::where('task_group', $task->task_group)->max('position') ?? 0) + 1;
        $task->save();

        return $task;
    }

    /**
     * Get a validator for an incoming project creation request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function taskCreationValidator(array $data) {
        return Validator::make($data, [
            'name' => 'required|string|min:4|max:255',
            'description' => 'string|min:6|max:512',
            'task_group' => 'required|integer'
        ]);
    }

    /**
     * Mark a task as completed. Used by the Web API.
     * 
     * @param int $id the task id
     * @return \Illuminate\Http\JsonResponse the JSON response to the API
     */
    public function complete(int $id) {
        $task = Task::findOrFail($id);

        $this->authorize('completeTask', $task);

        $task->state = 'completed';
        $task->save();

        return new JsonResponse($task->toArray());
    }

    public function search(Request $request, int $projectId) {

        $searchTerm = $request->query('q') ?? '';

        $project = Project::findOrFail($projectId);

        $user = Auth::user();

        // TODO: find out why the policy does not work, maybe wrong naming conventions ?
        // $this->authorize('search');
        if (!$user->is_admin && !$project->users->contains($user)) {
            abort(403);
        }

        $tasks = $this->searchTasks($searchTerm, $project);

        return view('pages.search.tasks', ['tasks' => $tasks]);
    }

    public function searchTasks(string $searchTerm, Project $project) {
        return $project->tasks()
            ->whereRaw('(task.fts_search @@ plainto_tsquery(\'english\', ?) OR task.name = ?)', [$searchTerm, $searchTerm])
            ->orderByRaw('ts_rank(task.fts_search, plainto_tsquery(\'english\', ?)) DESC', [$searchTerm])
            ->paginate(10);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        //
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show(int $project_id, $id) {
        $task = Task::findOrFail($id);

        if ($project_id !== $task->project->id) {
            abort(400, 'Task with id ' . $task->id . ' does not belong to project with id ' . $project_id);
        }

        $this->authorize('view', $task);

        $project = Project::findOrFail($project_id);

        return view('pages.task', ['task' => $task, 'project' => $project]);
    }

    public function edit(Request $request, int $project_id, int $id) {

        $requestData = $request->all();

        // TODO: implement policies

        $task = $this->editTask($id, $requestData);

        return $request->wantsJson()
            ? new JsonResponse($task->toArray(), 201)
            : redirect()->route('project.task.info', ['id' => $project_id, 'taskId' => $task->id]);
    }

    public function editTask(int $id, array $data) {
        $task = Task::findOrFail($id);

        if ($data['task_group'] !== null)
            $task->task_group = $data['task_group'];

        $task->save();

        return $task;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Task $task) {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function delete(Request $request, $id) {
        $task = Task::findOrFail($id);

        //$this->authorize('delete', $task);
        $task->delete();

        return $task;
    }
}