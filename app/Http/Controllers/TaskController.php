<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\TaskGroup;
use App\Models\TaskComment;
use App\Models\Project;
use App\Models\Tag;
use App\Models\User;
use App\Notifications\TaskCompleted;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TaskController extends Controller {

    public function store(Request $request) {
        $this->taskCreationValidator($request)->validate();

        $task_group = TaskGroup::findOrFail($request->input('task_group_id'));
        $project = Project::findOrFail($task_group->project_id);

        $this->authorize('edit', $project);
        $this->authorize('create', [Task::class, $task_group]);

        $task = $this->createTask($request, $task_group);

        return $request->wantsJson()
            ? response()->json($task, 201)
            : redirect()->route('project', ['project' => $project]);
    }

    public function createTask(Request $request, TaskGroup $task_group) {

        $task = new Task();
        $data = $request->all();

        $task->name = $data['name'];
        $task->description = $data['description'] ?? '';
        $task->task_group_id = $task_group->id;
        $task->position = (Task::where('task_group_id', $task->task_group_id)->max('position') ?? 0) + 1;
        $task->creator_id = $request->user()->id;

        $task->save();

        try {
            foreach ($data['tags'] ?? [] as $tagId) {
                $tag = Tag::findOrFail($tagId);
                $task->tags()->save($tag);
            }

            foreach ($data['assignees'] ?? [] as $assigneeId) {
                $assignee = User::findOrFail($assigneeId);
                $task->assignees()->save($assignee);
            }
        } catch (Exception $e) {
            $task->delete();

            throw $e;
        }

        return $task->fresh();
    }

    /**
     * Get a validator for an incoming task creation request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function taskCreationValidator(Request $request) {
        return Validator::make($request->all(), [
            'name' => 'required|string|min:4|max:255',
            'description' => 'nullable|string|min:6|max:512',
            'task_group_id' => 'required|integer',
            'assignees' => 'nullable|array|max:5',
            'tags' => 'nullable|array|max:5',
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

        return response()->json($task);
    }

    public function incomplete(Task $task) {

        $this->authorize('edit', $task->project);
        $this->authorize('incompleteTask', $task);

        $task->completed = false;
        $task->save();

        return response()->json($task);
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

        $this->editTaskValidator($request)->validate();

        $this->authorize('edit', $task->project);
        $this->authorize('edit', $task);

        $task = $this->editTask($task, $request);

        return $request->wantsJson()
            ? response()->json($task)
            : redirect()->route('project.task.info', ['project' => $project, 'task' => $task]);
    }

    public function editTask(Task $task, Request $request) {

        $data = $request->all();

        if (($data['task_group_id'] ??= null) !== null)
            $task->task_group_id = $data['task_group_id'];

        if (($data['position'] ??= null) !== null)
            $task->position = $data['position'];

        if (($data['description'] ??= null) !== null)
            $task->description = $data['description'];

        if (($data['name'] ??= null) !== null)
            $task->name = $data['name'];

        $task->tags()->detach();
        $task->assignees()->detach();
        foreach ($data['tags'] ?? [] as $tagId) {
            $tag = Tag::findOrFail($tagId);
            $task->tags()->save($tag);
        }

        foreach ($data['assignees'] ?? [] as $assigneeId) {
            $assignee = User::findOrFail($assigneeId);
            $task->assignees()->save($assignee);
        }

        if ($task->isDirty(['name', 'description']))
            $task->edit_date = now();

        $task->push();

        return $task->fresh();
    }

    public function editTaskValidator(Request $request) {
        return Validator::make($request->all(), [
            'name' => 'string|min:4|max:255',
            'description' => 'nullable|string|min:6|max:512',
            'task_group_id' => 'integer',
            'position' => 'integer|min:0',
            'assignees' => 'nullable|array|max:5',
            'tags' => 'nullable|array|max:5',
        ]);
    }

    public function createComment(Request $request, Project $project, Task $task) {
        $data = $request->all();

        $this->authorize('edit', $task);

        $task_comment = new TaskComment();

        $task_comment->content = $data['content'];
        $task_comment->author_id = $request->user()->id;
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