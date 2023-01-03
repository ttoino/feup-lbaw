<?php

namespace App\Http\Controllers;

use App\Models\Thread;
use App\Models\Project;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class ThreadController extends Controller {
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
    public function create(Request $request, Project $project) {
        $this->authorize('viewCreationForm', [Thread::class, $project]);

        return response()->view('pages.project.forum.new', ['project' => $project]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {

        $requestData = $request->all();

        $this->threadCreationValidator($requestData)->validate();

        $project = Project::findOrFail($requestData['project_id']);

        $this->authorize('edit', $project);
        $this->authorize('create', [Thread::class, $project]);

        $thread = $this->createThread($requestData, $project);

        return $request->wantsJson()
            ? response()->json($thread->toArray(), 201)
            : redirect()->route('project.thread', ['project' => $project, 'thread' => $thread]);
    }

    public function createThread(array $data, Project $project) {

        $thread = new Thread();

        $thread->title = $data['title'];
        $thread->content = $data['content'];
        $thread->author_id = Auth::user()->id;

        $project->threads()->save($thread);

        return $thread->fresh();
    }

    public function threadCreationValidator(array $data) {
        return Validator::make($data, [
            'title' => 'required|string|min:6|max:50',
            'content' => 'required|string|min:6|max:512',
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Thread  $thread
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Project $project, Thread $thread) {

        $this->authorize('view', $thread);

        $thread->comments = $thread->comments()->cursorPaginate(10);

        return $request->wantsJson()
            ? response()->json($thread)
            : response()->view('pages.project.thread', ['project' => $project, 'thread' => $thread]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Thread  $thread
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Thread $thread) {
        
        $requestData = $request->all();
        
        $this->threadEditionValidator($requestData)->validate();

        $this->authorize('edit', $thread->project);
        $this->authorize('update', $thread);

        $thread = $this->editThread($thread, $requestData);

        return response()->json($thread);
    }

    public function threadEditionValidator(array $data) {
        return Validator::make($data, [
            'title' => 'string|min:6|max:50',
            'content' => 'string|min:6|max:512',
        ]);
    }

    public function editThread(Thread $thread, array $data) {

        if (($data['name'] ??= null) !== null)
            $thread->name = $data['name'];
            
        if (($data['content'] ??= null) !== null)
            $thread->name = $data['content'];

        if ($thread->dirty())
            $thread->edit_date = now();

        return $thread;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Thread  $thread
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Thread $thread) {

        $this->authorize('edit', $thread->project);
        $this->authorize('delete', $thread);

        $thread->delete();

        return response()->json($thread);
    }
}