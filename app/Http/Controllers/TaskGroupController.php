<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\TaskGroup;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class TaskGroupController extends Controller {

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function createTaskGroup(Request $request) {
        $requestData = $request->all();

        $this->taskGroupCreationValidator($requestData)->validate();
        
        $project = Project::findOrFail($requestData['project_id']);

        $this->authorize('create', [TaskGroup::class, $project]);

        $taskGroup = $this->create($requestData);

        return $request->wantsJson()
            ? new JsonResponse([$taskGroup], 201)
            : redirect()->route('project', ['project' => $project]);
    }

    public function create(array $data) {

        $taskGroup = new TaskGroup();

        $taskGroup->name = $data['name'];
        $taskGroup->description = $data['description'] ?? '';
        $taskGroup->project_id = $data['project_id'];
        $taskGroup->position = (TaskGroup::where('project_id', $taskGroup->project_id)->max('position') ?? 0) + 1;
        $taskGroup->save();

        return $taskGroup;
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

    public function repositionTaskGroup(Request $request, TaskGroup $taskGroup) {

        $requestData = $request->all();

        $this->taskGroupRepositionValidator($requestData)->validate();

        $this->authorize('reposition', $taskGroup);
        $this->authorize('edit', $taskGroup->project);

        $taskGroup = $this->reposition($taskGroup, $requestData);

        return new JsonResponse($taskGroup, 200);
    }

    protected function taskGroupRepositionValidator(array $data) {
        return Validator::make($data, [
            'position' => 'integer|min:0',
        ]);
    }

    public function reposition(TaskGroup $taskGroup, array $data) {

        if (($data['position'] ??= null) !== null)
            $taskGroup->position = $data['position'];

        $taskGroup->save();
        return $taskGroup;
    }

    public function delete(Request $request, TaskGroup $taskGroup) {

        //$this->authorize('delete', $task);
        $taskGroup->delete();

        return $taskGroup;
    }
}