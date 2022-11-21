<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

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
    public function create(Request $request) {
        $task = new Task();

        //$this->authorize('create', $task);

        $task->name = $request->input('name');
        $task->description = $request->input('description');
        $task->task_group = $request->input('task_group');
        $task->position = $request->input('position');
        $task->save();

        return $task;
    }

    /**
     * Mark a task as completed. Used by the Web API.
     * 
     * @param int $id the task id
     * @return \Illuminate\Http\JsonResponse the JSON response to the API
     */
    public function complete(int $id) {
        $task = Task::findOrFail($id);

        $this->authorize('complete', $task);

        $task->state = 'COMPLETED';
        $task->save();

        return new JsonResponse([$task]);
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
    public function show($project_id, $id) {
        $task = Task::find($id);
        $project = $task->project;
        //$this->authorize('show', $task);
        return view('pages.task', ['task' => $task], ['project' => $project]);
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
