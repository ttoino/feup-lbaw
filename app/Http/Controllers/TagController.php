<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use App\Models\Project;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class TagController extends Controller {

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {

        $this->tagCreationValidator($request)->validate();

        $project = Project::findOrFail($request->input('project_id'));

        $this->authorize('edit', $project);
        $this->authorize('create', [Tag::class, $project]);

        $tag = $this->createTag($project, $request);

        return $request->wantsJson()
            ? response()->json($tag->toArray(), 201)
            : redirect()->route('project.tag', ['project' => $project, 'tag' => $tag]);
    }

    public function createTag(Project $project, Request $request) {

        $tag = new Tag();
        $data = $request->only(['title', 'color']);

        $tag->title = $data['title'];
        $tag->color = intval(substr($data['color'], 1), 16);

        $project->tags()->save($tag);

        return $tag->fresh();
    }

    public function tagCreationValidator(Request $request) {
        return Validator::make($request->all(), [
            'title' => 'required|string|min:6|max:50',
            'color' => 'required|string|regex:/^#[0-9a-f]{6}$/',
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Tag  $tag
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Project $project, Tag $tag) {

        $this->authorize('view', $tag);

        $tag->comments = $tag->comments()->cursorPaginate(10);

        return response()->json($tag);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Tag  $tag
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Tag $tag) {
        $this->tagEditionValidator($request)->validate();

        $this->authorize('edit', $tag->project);
        $this->authorize('update', $tag);

        $tag = $this->editTag($tag, $request);

        return response()->json($tag);
    }

    public function tagEditionValidator(Request $request) {
        return Validator::make($request->all(), [
            'title' => 'required|string|min:6|max:50',
            'color' => 'required|string|regex:/^#[0-9a-f]{6}$/',
        ]);
    }

    public function editTag(Tag $tag, Request $request) {

        $data = $request->only(['title', 'color']);

        if (($data['title'] ??= null) !== null)
            $tag->title = $data['title'];

        if (($data['color'] ??= null) !== null)
            $tag->color = intval(substr($data['color'], 1), 16);

        $tag->save();

        return $tag->fresh();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Tag  $tag
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Tag $tag) {

        $this->authorize('edit', $tag->project);
        $this->authorize('delete', $tag);

        $tag->delete();

        return response()->json($tag);
    }
}