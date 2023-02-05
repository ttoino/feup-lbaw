<?php

namespace App\Http\Controllers;

use App\Models\ThreadComment;
use App\Models\Thread;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

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

        return response()->json($comments);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        $this->threadCommentCreationValidator($request)->validate();

        $thread = Thread::findOrFail($request->input('thread_id'));

        $this->authorize('edit', $thread->project);
        $this->authorize('create', [ThreadComment::class, $thread]);

        $threadComment = $this->createThreadComment($request, $thread);

        return response()->json($threadComment);
    }

    public function threadCommentCreationValidator(Request $request) {
        return Validator::make($request->all(), [
            'content' => 'required|string|min:0|max:512',
            'thread_id' => 'required|integer',
        ]);
    }

    public function createThreadComment(Request $request, Thread $thread) {

        $threadComment = new ThreadComment();

        $data = $request->only(['content']);

        $threadComment->content = $data['content'];
        $threadComment->author_id = $request->user()->id;
        $threadComment->thread_id = $thread->id;

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

        return response()->json($threadComment);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ThreadComment  $threadComment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ThreadComment $threadComment) {

        $this->threadCommentEditionValidator($request)->validate();

        $thread = $threadComment->thread;

        $this->authorize('edit', $thread->project);
        $this->authorize('update', $threadComment);

        $threadComment = $this->updateThreadComment($threadComment, $request);

        return response()->json($threadComment);

    }

    public function threadCommentEditionValidator(Request $request) {
        return Validator::make($request->all(), [
            'content' => 'string|min:0|max:512',
        ]);
    }

    public function updateThreadComment(ThreadComment $threadComment, Request $request) {

        $data = $request->all(['content']);

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

        return response()->json($threadComment);
    }
}