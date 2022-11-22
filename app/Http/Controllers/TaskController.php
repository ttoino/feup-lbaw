<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\TaskGroup;
use App\Models\Project;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
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
            : redirect()->route('project.home', ['id' => $projectId]);
    }

    public function create(array $data) {

        $task = new Task();

        $task->name = $data['name'];
        $task->description = $data['description'] ?? '';
        $task->task_group = $data['task_group'];
        $task->position = $data['position'];
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
            'position' => 'required|integer|min:0',
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

        // TODO: this should be uncommented when developing the final version
        // $this->authorize('complete', $task);

        $task->state = 'completed';
        $task->save();

        return new JsonResponse($task->toArray());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show(int $project_id, $id) {
        $task = Task::find($id);

        if ($project_id !== $task->project->id) {
            abort(400, 'Task with id ' . $task->id . ' does not belong to project with id ' . $project_id);
        }
        
        $this->authorize('view', $task);

        $other_projects = Auth::user()->projects->except($project_id);
        $project = Project::findOrFail($project_id);

        return view('pages.task', ['task' => $task], ['project' => $project, 'other_projects' => $other_projects]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function edit(Task $task)
    {
        //
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
        $task = Task::find($id);

        //$this->authorize('delete', $task);
        $task->delete();

        return $task;
    }
}
