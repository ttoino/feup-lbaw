@extends('layouts.project')

@section('title', $project->name)
@push('main-classes', 'project-board-main ')

@push('templates')
    <template id="tag-template">@include('partials.project.board.task-tag')</template>
    <template id="assignee-template">@include('partials.project.board.task-assignee')</template>
    <template id="tag-template">@include('partials.project.board.task-tag', [
        'project' => $project,
    ])</template>
    <template id="task-template">@include('partials.project.board.task')</template>
    <template id="task-comment-template">@include('partials.project.board.comment')</template>
    <template id="task-group-template">@include('partials.project.board.task-group', [
        'group' => new \App\Models\TaskGroup(),
        'project' => $project,
    ])</template>
@endpush

@section('project-content')
    <section class="project-board">
        @each('partials.project.board.task-group', $project->taskGroups, 'group')

        @can('edit', $project)
            @include('partials.project.board.task-group')

            <a id="new-task-button" data-bs-toggle="offcanvas" href="#new-task-offcanvas"
                role="button" @class([
                    'disabled' => $project->archived,
                ])
                aria-controls="new-task-offcanvas">
                <i class="bi bi-plus"></i> Create task
            </a>
        @endcan
    </section>

    <aside id="task-offcanvas" @class(['show' => $show_task ?? false, 'offcanvas', 'loader'])>
        @include('partials.project.task')
        @include('partials.loading')
    </aside>

    @can('edit', $project)
        <aside id="new-task-offcanvas">
            <header class="offcanvas-header">
                <h2 class="offcanvas-title h4" id="new-task-offcanvas-title">
                    New task
                </h2>
                <button type="button" class="btn-close" data-bs-dismiss="offcanvas"
                    data-bs-target="#new-task-offcanvas" aria-label="Close"></button>
            </header>
            @include('partials.project.task.new')
        </aside>
    @endcan
@endsection
