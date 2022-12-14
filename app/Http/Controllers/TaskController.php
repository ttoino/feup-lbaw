<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\TaskGroup;
use App\Models\Project;
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
    public function createTask(Request $request, Project $project) {
        $requestData = $request->all();

        $this->taskCreationValidator($requestData)->validate();

        $task_group = TaskGroup::findOrFail($requestData['task_group_id']); 

        $this->authorize('create', [Task::class, $task_group]);

        $task = $this->create($requestData);

        return $request->wantsJson()
            ? new JsonResponse([$task], 201)
            : redirect()->route('project', ['project' => $project]);
    }

    public function create(array $data) {

        $task = new Task();

        $task->name = $data['name'];
        $task->description = $data['description'] ?? '';
        $task->task_group_id = $data['task_group_id'];
        $task->position = (Task::where('task_group_id', $task->task_group_id)->max('position') ?? 0) + 1;
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
            'description' => 'nullable|string|min:6|max:512',
            'task_group_id' => 'required|integer'
        ]);
    }

    /**
     * Mark a task as completed. Used by the Web API.
     * 
     * @param int $id the task id
     * @return \Illuminate\Http\JsonResponse the JSON response to the API
     */
    public function complete(Task $task) {

        $this->authorize('completeTask', $task);

        $task->state = 'completed';
        $task->save();

        return new JsonResponse($task->toArray());
    }

    public function search(Request $request, Project $project) {

        $searchTerm = $request->query('q') ?? '';

        $this->authorize('search', [Task::class, $project]);

        $tasks = $this->searchTasks($searchTerm, $project);

        return view('pages.search.tasks', ['tasks' => $tasks->withQueryString()]);
    }

    public function searchTasks(string $searchTerm, Project $project) {

        $projectTasks = $project->tasks();

        if (!empty($searchTerm))
            $projectTasks = $projectTasks->whereRaw('(task.fts_search @@ plainto_tsquery(\'english\', ?) OR task.name = ?)', [$searchTerm, $searchTerm])
            ->orderByRaw('ts_rank(task.fts_search, plainto_tsquery(\'english\', ?)) DESC', [$searchTerm]);
        
        return $projectTasks->paginate(10);
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
    public function show(Request $request, Project $project, Task $task) {

        if ($project->id !== $task->project->id) {
            abort(400, 'Task with id ' . $task->id . ' does not belong to project with id ' . $project->id);
        }

        $this->authorize('view', $task);

        return view('pages.task', ['task' => $task, 'project' => $project]);
    }

    public function edit(Request $request, Project $project, Task $task) {

        $requestData = $request->all();

        $this->editTaskValidator($requestData)->validate();

        $this->authorize('edit', $task);
        $this->authorize('edit', $task->project);

        $task = $this->editTask($task, $requestData);

        return $request->wantsJson()
            ? new JsonResponse($task->toArray(), 200)
            : redirect()->route('project.task.info', ['project' => $project, 'task' => $task]);
    }

    public function editTask(Task $task, array $data) {

        if (($data['task_group_id'] ??= null) !== null) {
            $task->task_group_id = $data['task_group_id'];
        }
            
        if (($data['position'] ??= null) !== null)
            $task->position = $data['position'];
            
        if (($data['description'] ??= null) !== null)
            $task->description = $data['description'];

        if (($data['name'] ??= null) !== null)
            $task->name = $data['name'];

        if ($task->isDirty())
            $task->edit_date = date('Y-m-d');
        
        $task->save();

        return $task;
    }

    public function editTaskValidator(array $data) {
        return Validator::make($data, [
            'name' => 'string|min:4|max:255',
            'description' => 'string|min:6|max:512',
            'task_group_id' => 'integer', 
            'position' => 'integer|min:0',
        ]);
    }

    public function repositionTask(Request $request, Task $task) {

        $requestData = $request->all();

        $this->editTaskValidator($requestData)->validate();

        $this->authorize('edit', $task);
        $this->authorize('edit', $task->project);

        $task = $this->editTask($task, $requestData);

        return new JsonResponse($task->toArray(), 200);
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