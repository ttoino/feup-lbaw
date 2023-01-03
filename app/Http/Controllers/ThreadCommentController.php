<?php

namespace App\Http\Controllers;

use App\Models\ThreadComment;
use App\Models\Thread;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class ThreadCommentController extends Controller {
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request) {
        
        $threadId = $request->query('thread_id');

        $thread = Thread::findOrFail($threadId);

        $this->authorize('viewAny', [ThreadComment::class, $thread]);

        $comments = ThreadComment::cursorPaginate(10);

        return new JsonResponse($comments);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        $requestData = $request->all();

        $this->threadCommentCreationValidator($requestData)->validate();

        $thread = Thread::findOrFail($requestData['thread_id']);

        $this->authorize('edit', $thread->project);
        $this->authorize('create', [ThreadComment::class, $thread]);

        $threadComment = $this->createThreadComment($requestData);

        return new JsonResponse($threadComment);
    }

    public function threadCommentCreationValidator(array $data) {
        return Validator::make($data, [
            'content' => 'required|string|min:0|max:512',
            'thread_id' => 'required|integer',
        ]);
    }

    public function createThreadComment(array $data) {

        $threadComment = new ThreadComment();

        $threadComment->content = $data['content'];
        $threadComment->author_id = Auth::user()->id;
        $threadComment->thread_id = $data['thread_id'];
        $threadComment->creation_date = now();

        $threadComment->save();

        return $threadComment->fresh();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ThreadComment  $threadComment
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, ThreadComment $threadComment) {
        $this->authorize('view', [$threadComment]);

        return new JsonResponse($threadComment);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ThreadComment  $threadComment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ThreadComment $threadComment) {

        $requestData = $request->all();

        $this->threadCommentEditionValidator($requestData)->validate();

        $thread = $threadComment->thread;

        $this->authorize('edit', $thread->project);
        $this->authorize('update', $threadComment);

        $threadComment = $this->updateThreadComment($threadComment, $requestData);

        return new JsonResponse($threadComment);

    }

    public function threadCommentEditionValidator(array $data) {
        return Validator::make($data, [
            'content' => 'string|min:0|max:512',
        ]);
    }

    public function updateThreadComment(ThreadComment $threadComment, array $data) {

        if (($data['content'] ??= null) !== null)
            $threadComment->content = $data['content'];

        if ($threadComment->isDirty())
            $threadComment->edit_date = now();

        $threadComment->save();

        return $threadComment->fresh();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ThreadComment  $threadComment
     * @return \Illuminate\Http\Response
     */
    public function destroy(ThreadComment $threadComment) {

        $this->authorize('edit', $threadComment->thread->project);
        $this->authorize('delete', $threadComment);

        $threadComment->delete();

        return new JsonResponse($threadComment);
    }
}