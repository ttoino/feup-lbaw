<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\TaskGroup;
use App\Models\TaskComment;
use App\Models\Project;
use App\Notifications\TaskCompleted;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller {

    public function store(Request $request) {
        $requestData = $request->all();

        $this->taskCreationValidator($requestData)->validate();

        $task_group = TaskGroup::findOrFail($requestData['task_group_id']);
        $project = Project::findOrFail($task_group->project_id);

        $this->authorize('edit', $project);
        $this->authorize('create', [Task::class, $task_group]);

        $task = $this->createTask($requestData);

        return $request->wantsJson()
            ? response()->json($task, 201)
            : redirect()->route('project', ['project' => $project]);
    }

    public function createTask(array $data) {

        $task = new Task();

        $task->name = $data['name'];
        $task->description = $data['description'] ?? '';
        $task->task_group_id = $data['task_group_id'];
        $task->position = (Task::where('task_group_id', $task->task_group_id)->max('position') ?? 0) + 1;
        $task->creator_id = Auth::user()->id;
        $task->save();

        return $task->fresh();
    }

    /**
     * Get a validator for an incoming task creation request.
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
     * @param Task $task the task to complete
     * @return \Illuminate\Http\JsonResponse the JSON response to the API
     */
    public function complete(Task $task) {

        $this->authorize('edit', $task->project);
        $this->authorize('completeTask', $task);

        $task->completed = true;
        $task->save();

        foreach ($task->assignees as $assignee) {
            $assignee->notify(new TaskCompleted($task));
        }

        return response()->json($task->toArray());
    }

    public function incomplete(Task $task) {

        $this->authorize('edit', $task->project);
        $this->authorize('incompleteTask', $task);

        $task->completed = false;
        $task->save();

        foreach ($task->assignees as $assignee) {
            // TODO
            // $assignee->notify(new TaskCompleted($task));
        }

        return response()->json($task->toArray());
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Project $project, Task $task) {

        $isApi = $request->expectsJson();

        $project = $isApi ? $task->project : $project;

        $this->authorize('view', [$task, $project]);

        $task->comments = $task->comments()->cursorPaginate(10);

        return $isApi
            ? response()->json($task)
            : response()->view('pages.project.task', ['task' => $task, 'project' => $project]);
    }

    public function update(Request $request, Project $project, Task $task) {

        $requestData = $request->all();

        $this->editTaskValidator($requestData)->validate();

        $this->authorize('edit', $task->project);
        $this->authorize('edit', $task);

        $task = $this->editTask($task, $requestData);

        return $request->wantsJson()
            ? response()->json($task->toArray(), 200)
            : redirect()->route('project.task.info', ['project' => $project, 'task' => $task]);
    }

    public function editTask(Task $task, array $data) {

        if (($data['task_group_id'] ??= null) !== null)
            $task->task_group_id = $data['task_group_id'];

        if (($data['position'] ??= null) !== null)
            $task->position = $data['position'];

        if (($data['description'] ??= null) !== null)
            $task->description = $data['description'];

        if (($data['name'] ??= null) !== null)
            $task->name = $data['name'];

        if ($task->isDirty(['name', 'description']))
            $task->edit_date = now();

        $task->save();

        return $task;
    }

    public function editTaskValidator(array $data) {
        return Validator::make($data, [
            'name' => 'string|min:4|max:255',
            'description' => 'nullable|string|min:6|max:512',
            'task_group_id' => 'integer',
            'position' => 'integer|min:0',
        ]);
    }

    public function createComment(Request $request, Project $project, Task $task) {
        $data = $request->all();

        $this->authorize('edit', $task);

        $task_comment = new TaskComment();

        $task_comment->content = $data['content'];
        $task_comment->author_id = Auth::user()->id;
        $task_comment->task_id = $task->id;
        $task_comment->save();

        return $request->wantsJson()
            ? response()->json([$task_comment], 201)
            : redirect()->route('project.task.info', ['project' => $project, 'task' => $task]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Task $task) {

        $this->authorize('delete', $task);
        $task->delete();

        return response()->json($task);
    }
}