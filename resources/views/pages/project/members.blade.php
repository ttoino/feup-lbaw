@extends('layouts.project')
@php
    $paginator = $members;
    $itemView = 'partials.list-item.user';
@endphp

@section('empty-list')
    <div class="vstack align-items-center justify-content-center h-100">
        <p class="display-5">No members match the search term!</p>

        <form method="GET" action="{{ route('project.members', ['project' => $project]) }}" class="input-group" role="search"
            style="max-width: 360px">
            <input class="form-control" name="q" type="search" placeholder="Search members" aria-label="Search"
                value="{{ Request::query('q', '') }}">
            <button class="btn btn-outline-primary" type="submit"><i class="bi bi-search"></i></button>
        </form>
    </div>
@endsection

@section('list-title')
    <div class="hstack justify-content-between gap-3">
        <h2>Members</h2>

        <form method="GET" action="{{ route('project.members', ['project' => $project]) }}" class="input-group"
            role="search" style="max-width: 360px">
            <input class="form-control" name="q" type="search" placeholder="Search members" aria-label="Search"
                value="{{ Request::query('q', '') }}">
            <button class="btn btn-outline-primary" type="submit"><i class="bi bi-search"></i></button>
        </form>
    </div>
@endsection

@section('project-content')
    <section class="flex-column p-3 d-flex container gap-3 narrow">
        @include('partials.paginated-list')

        @can('create', [App\Models\Tag::class, $project])
            <form class="invite-user-form input-group">
                <input type="email" minlength="6" maxlength="50" required name="email" placeholder="Invite a new user"
                    class="form-control">
                <button class="btn btn-primary" type="submit">
                    <i class="bi bi-plus"></i>
                </button>
            </form>
        @endcan
    </section>
@endsection
