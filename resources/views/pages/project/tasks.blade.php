@extends('layouts.project')
@php
    $paginator = $tasks;
    $itemView = 'partials.list-item.task';
@endphp

@section('empty-list')
    <div class="vstack align-items-center justify-content-center h-100">
        <p class="display-5">No tasks match the search term!</p>

        <form method="GET"
            action="{{ route('project.tasks', ['project' => $project]) }}"
            class="input-group" role="search" style="max-width: 360px">
            <input class="form-control" name="q" type="search"
                placeholder="Search tasks" aria-label="Search"
                value="{{ Request::query('q', '') }}">
            <button class="btn btn-outline-primary" type="submit"><i
                    class="bi bi-search"></i></button>
        </form>
    </div>
@endsection

@section('list-title')
    <div class="hstack justify-content-between gap-3">
        <h2>Tasks</h2>

        <form method="GET"
            action="{{ route('project.tasks', ['project' => $project]) }}"
            class="input-group" role="search" style="max-width: 360px">
            <input class="form-control" name="q" type="search"
                placeholder="Search tasks" aria-label="Search"
                value="{{ Request::query('q', '') }}">
            <button class="btn btn-outline-primary" type="submit"><i
                    class="bi bi-search"></i></button>
        </form>
    </div>
@endsection

@section('project-content')
    <section class="flex-column p-3 d-flex container gap-3 narrow">
        @include('partials.paginated-list')
    </section>
@endsection
