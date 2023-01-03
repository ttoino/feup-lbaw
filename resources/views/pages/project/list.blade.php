@extends('layouts.paginated-list')
@php
    $paginator = $projects;
    $itemView = 'partials.list-item.project';
@endphp

@section('empty-list')
    <div class="vstack align-items-center justify-content-center">
        <p class="display-6">You don't have any projects yet!</p>
        <a href="{{ route('project.new') }}" class="btn btn-lg btn-primary"><i
                class="bi bi-plus"></i> Create your first</a>
    </div>
@endsection

@section('list-title')
    <div class="hstack gap-2 justify-content-between align-content-end flex-wrap">
        <h2 class="flex-fill">Your projects</h2>
        <a href="{{ route('project.new') }}" class="btn btn-primary">
            <i class="bi bi-plus"></i> Create project
        </a>
        <form method="GET" action="{{ route('project.list') }}" role="search"
            class="input-group" style="max-width: 360px">
            <input class="form-control" name="q" type="search"
                placeholder="Search projects" aria-label="Search"
                value="{{ Request::query('q', '') }}">
            <button class="btn btn-outline-primary" type="submit"><i
                    class="bi bi-search"></i></button>
        </form>
    </div>
@endsection
