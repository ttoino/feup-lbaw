<?php

namespace App\Http\Controllers;

use App\Models\Task;
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

        //$this->taskCreationValidator($requestData)->validate();

        //$this->authorize('create', $task);

        $taskGroup = $this->create($requestData);

        $projectId = TaskGroup::findOrFail($taskGroup->id)->project;

        return $request->wantsJson()
            ? new JsonResponse([$taskGroup], 201)
            : redirect()->route('project', ['id' => $projectId]);
    }

    public function create(array $data) {

        $taskGroup = new TaskGroup();

        $taskGroup->name = $data['name'];
        $taskGroup->description = $data['description'] ?? '';
        $taskGroup->project = $data['project'];
        $taskGroup->position = $data['position'];
        $taskGroup->save();

        return $taskGroup;
    }

    /**
     * Get a validator for an incoming project creation request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function taskCreationValidator(array $data) {
        return Validator::make($data, [
            'name' => 'required|string|min:4|max:255',
            'description' => 'string|min:6|max:512',
            'position' => 'required|integer|min:0',
            'task_group' => 'required|integer'
        ]);
    }




    public function delete(Request $request, $id) {
        $taskGroup = TaskGroup::findOrFail($id);

        //$this->authorize('delete', $task);
        $taskGroup->delete();

        return $taskGroup;
    }
}