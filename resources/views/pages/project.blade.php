@extends('layouts.project')

@section('title', $project->name)
@push('main-classes', 'overflow-auto ')

@section('project-content')
    <section style="overflow-x: auto"
        class="flex-fill d-flex flex-row gap-3 p-3 align-items-start flex-fill">

        @each('partials.project.board.task-group', $project->taskGroups, 'group')
        @include('partials.project.board.task-group')
@endsection 
