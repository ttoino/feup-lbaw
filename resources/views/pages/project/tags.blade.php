@extends('layouts.project')
@php
    $paginator = $tags;
    $itemView = 'partials.list-item.tag';
@endphp

@section('empty-list')
    <div class="vstack align-items-center justify-content-center h-100">
        <p class="display-5">No tags match the search term!</p>

        <form method="GET" action="{{ route('project.tags', ['project' => $project]) }}" class="input-group" role="search"
            style="max-width: 360px">
            <input class="form-control" name="q" type="search" placeholder="Search tags" aria-label="Search"
                value="{{ Request::query('q', '') }}">
            <button class="btn btn-outline-primary" type="submit"><i class="bi bi-search"></i></button>
        </form>
    </div>
@endsection

@section('list-title')
    <div class="hstack justify-content-between gap-3">
        <h2>Tags</h2>

        <form method="GET" action="{{ route('project.tags', ['project' => $project]) }}" class="input-group"
            role="search" style="max-width: 360px">
            <input class="form-control" name="q" type="search" placeholder="Search tags" aria-label="Search"
                value="{{ Request::query('q', '') }}">
            <button class="btn btn-outline-primary" type="submit"><i class="bi bi-search"></i></button>
        </form>
    </div>
@endsection

@section('project-content')
    <section class="flex-column p-3 d-flex container gap-3 narrow">
        @include('partials.paginated-list')

        @if (!$project->archived)
            @can('create', [App\Models\Tag::class, $project])
                <form class="new-tag-form input-group">
                    <input class="form-control form-control-color" type="color" name="color" required
                        style="max-width: 38px">
                    <input type="text" minlength="6" maxlength="50" required name="title" placeholder="Create a new tag"
                        class="form-control">
                    <button class="btn btn-primary" type="submit">
                        <i class="bi bi-plus"></i>
                    </button>
                </form>
            @endcan
        @endif
    </section>
@endsection
