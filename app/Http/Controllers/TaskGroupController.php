<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\TaskGroup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TaskGroupController extends Controller {

    public function show(Request $request, TaskGroup $taskGroup) {

        $this->authorize('view', $taskGroup);

        return response()->json($taskGroup);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        $requestData = $request->all();

        $this->taskGroupCreationValidator($requestData)->validate();

        $project = Project::findOrFail($requestData['project_id']);

        $this->authorize('edit', $project);
        $this->authorize('create', [TaskGroup::class, $project]);

        $taskGroup = $this->createTaskGroup($requestData);

        return $request->wantsJson()
            ? response()->json($taskGroup, 201)
            : redirect()->route('project', ['project' => $project]);
    }

    public function createTaskGroup(array $data) {

        $taskGroup = new TaskGroup();

        $taskGroup->name = $data['name'];
        $taskGroup->description = $data['description'] ?? '';
        $taskGroup->project_id = $data['project_id'];
        $taskGroup->position = (TaskGroup::where('project_id', $taskGroup->project_id)->max('position') ?? 0) + 1;
        $taskGroup->save();

        return $taskGroup->fresh();
    }

    /**
     * Get a validator for an incoming project creation request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function taskGroupCreationValidator(array $data) {
        return Validator::make($data, [
            'name' => 'required|string|min:4|max:255',
            'description' => 'string|min:6|max:512',
            'project_id' => 'required|integer'
        ]);
    }

    public function update(Request $request, TaskGroup $taskGroup) {

        $requestData = $request->all();

        $this->taskGroupUpdateValidator($requestData)->validate();

        $this->authorize('edit', $taskGroup->project);
        $this->authorize('update', $taskGroup);

        $taskGroup = $this->updateTaskGroup($taskGroup, $requestData);

        return response()->json($taskGroup);
    }

    protected function taskGroupUpdateValidator(array $data) {
        return Validator::make($data, [
            'position' => 'integer|min:0',
        ]);
    }

    public function updateTaskGroup(TaskGroup $taskGroup, array $data) {

        if (($data['position'] ??= null) !== null)
            $taskGroup->position = $data['position'];
        
        if (($data['name'] ??= null) !== null)
            $taskGroup->name = $data['name'];
        
        if (($data['position'] ??= null) !== null)
            $taskGroup->description = $data['description'];

        $taskGroup->save();
        return $taskGroup;
    }

    public function destroy(Request $request, TaskGroup $taskGroup) {

        $this->authorize('delete', $taskGroup);
        $taskGroup->delete();

        return response()->json($taskGroup);
    }
}