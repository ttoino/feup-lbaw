<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

use App\Models\Project;

class ProjectController extends Controller {

    public function show($id) {
        $project = Project::find($id);
        //$this->authorize('show', $project);
        return view('pages.project', ['project' => $project], ['id' => $id]);
    }

    /**
     * Shows user's projects.
     *
     * @return Response
     */
    public function list() {
        // $this->authorize('list', Project::class);
        $projects = Auth::user()->projects;
        return view('pages.home', ['projects' => $projects]);
    }

    /**
     * Creates a new project.
     *
     * @return Project The project created.
     */
    public function create(Request $request) {
        $project = new Project();

        //$this->authorize('create', $project);

        $project->name = $request->input('name');
        $project->archived = FALSE;
        $project->description = $request->input('description');
        $project->coordinator = Auth::user()->id;
        $project->save();

        return $project;
    }

    public function delete(Request $request, $id) {
        $project = Project::find($id);

        //$this->authorize('delete', $project);
        $project->delete();

        return $project;
    }
}