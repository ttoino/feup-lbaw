<?php

namespace App\Http\Controllers;

use App\Models\TaskComment;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class TaskCommentController extends Controller {
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request) {
        $taskId = $request->query('task_id');

        $task = Task::findOrFail($taskId);

        $this->authorize('viewAny', [TaskComment::class, $task]);

        $comments = TaskComment::cursorPaginate(10);

        return response()->json($comments);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        $this->taskCommentCreationValidator($request)->validate();

        $task = Task::findOrFail($request->input('task_id'));

        $this->authorize('edit', $task->project);
        $this->authorize('create', [TaskComment::class, $task]);

        $taskComment = $this->createTaskComment($request, $task);

        return response()->json($taskComment, 201);
    }

    public function taskCommentCreationValidator(Request $request) {
        return Validator::make($request->all(), [
            'content' => 'required|string|min:0|max:512',
            'task_id' => 'required|integer',
        ]);
    }

    public function createTaskComment(Request $request, Task $task) {

        $taskComment = new TaskComment();

        $taskComment->content = $request->input('content');
        $taskComment->author_id = $request->user()->id;
        $taskComment->task_id = $task->id;

        $taskComment->save();

        return $taskComment->fresh();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\TaskComment  $taskComment
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, TaskComment $taskComment) {
        $this->authorize('view', [$taskComment]);

        return response()->json($taskComment);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\TaskComment  $taskComment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, TaskComment $taskComment) {
        $this->taskCommentEditionValidator($request)->validate();

        $task = $taskComment->task;

        $this->authorize('edit', $task->project);
        $this->authorize('update', $taskComment);

        $taskComment = $this->updateTaskComment($taskComment, $request);

        return response()->json($taskComment);
    }

    public function taskCommentEditionValidator(Request $request) {
        return Validator::make($request->all(), [
            'content' => 'string|min:0|max:512',
        ]);
    }

    public function updateTaskComment(TaskComment $taskComment, Request $request) {

        $data = $request->only(['content']);

        if (($data['content'] ??= null) !== null)
            $taskComment->content = $data['content'];

        $taskComment->save();

        return $taskComment->fresh();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\TaskComment  $taskComment
     * @return \Illuminate\Http\Response
     */
    public function destroy(TaskComment $taskComment) {
        
        $this->authorize('edit', $taskComment->task->project);
        $this->authorize('delete', $taskComment);

        $taskComment->delete();

        return response()->json($taskComment);
    }
}
