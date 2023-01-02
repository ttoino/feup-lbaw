<?php

namespace App\Http\Controllers;

use App\Models\TaskComment;
use App\Models\Task;
use App\Models\User;
use Illuminate\Http\JsonResponse;
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
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        $requestData = $request->all();

        $this->taskCommentCreationValidator($requestData)->validate();

        $task = Task::findOrFail($requestData['task_id']);

        $this->authorize('edit', $task->project);
        $this->authorize('create', [TaskComment::class, $task]);

        $taskComment = $this->createTaskComment($requestData);

        return new JsonResponse($taskComment);
    }

    public function taskCommentCreationValidator(array $data) {
        return Validator::make($data, [
            'content' => 'required|string|min:0|max:512',
            'task_id' => 'required|integer',
        ]);
    }

    public function createTaskComment(array $data) {

        $taskComment = new TaskComment();

        $taskComment->content = $data['content'];
        $taskComment->author_id = Auth::user()->id;
        $taskComment->task_id = $data['task_id'];
        $taskComment->creation_date = now();

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

        return new JsonResponse($taskComment);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\TaskComment  $taskComment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, TaskComment $taskComment) {
        
        $requestData = $request->all();

        $this->taskCommentEditionValidator($requestData)->validate();

        $task = $taskComment->task;

        $this->authorize('edit', $task->project);
        $this->authorize('update', $taskComment);

        $taskComment = $this->updateTaskComment($taskComment, $requestData);

        return new JsonResponse($taskComment);

    }

    public function taskCommentEditionValidator(array $data) {
        return Validator::make($data, [
            'content' => 'string|min:0|max:512',
        ]);
    }

    public function updateTaskComment(TaskComment $taskComment, array $data) {

        if (($data['content'] ??= null) !== null)
            $taskComment->content = $data['content'];

        if ($taskComment->dirty())
            $taskComment->edit_date = now();

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

        return new JsonResponse($taskComment);
    }
}
