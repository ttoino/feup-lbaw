<?php

namespace App\Http\Controllers;

use App\Models\Thread;
use App\Models\Project;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class ThreadController extends Controller
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
    public function create(Request $request, Project $project) {
        $this->authorize('viewCreationForm', [Thread::class, $project]);
    
        return view('pages.project.forum.new', ['project' => $project]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Project $project) {

        $requestData = $request->all();

        $this->threadCreationValidator($requestData)->validate();
        
        $this->authorize('edit', $project);
        $this->authorize('create', [Thread::class, $project]);

        $thread = $this->createThread($requestData, $project);

        return $request->wantsJson()
            ? new JsonResponse($thread->toArray(), 201)
            : view('pages.project.forum.thread', ['project' => $project, 'thread' => $thread]);
    }

    public function createThread(array $data, Project $project) {

        $thread = new Thread();

        $thread->title = $data['title'];
        $thread->content = $data['content'];
        $thread->author_id = Auth::user()->id;
        
        $project->threads()->save($thread);
        
        return $thread;
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
    public function show(Project $project, Thread $thread){

        $this->authorize('view', $thread);

        return view('pages.project.forum.thread', ['project' => $project, 'thread' => $thread]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Thread  $thread
     * @return \Illuminate\Http\Response
     */
    public function edit(Thread $thread)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Thread  $thread
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Thread $thread)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Thread  $thread
     * @return \Illuminate\Http\Response
     */
    public function destroy(Thread $thread)
    {
        //
    }
}
