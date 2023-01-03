@extends('layouts.paginated-list')
@php
    $paginator = $projects;
    $itemView = 'partials.list-item.project';
@endphp

@section('empty-list')
    <div class="vstack align-items-center justify-content-center h-100">
        <p>No project match the search term!</p>

        <form method="GET" action="{{ route('admin.projects') }}" class="input-group"
            role="search" style="max-width: 360px">
            <input class="form-control" name="q" type="search"
                placeholder="Search projects" aria-label="Search"
                value="{{ Request::query('q', '') }}">
            <button class="btn btn-outline-primary" type="submit"><i
                    class="bi bi-search"></i></button>
        </form>
    </div>
@endsection

@section('list-title')
    @include('partials.admin.nav')

    <form method="GET" action="{{ route('admin.projects') }}" class="input-group"
        role="search" style="max-width: 360px">
        <input class="form-control" name="q" type="search"
            placeholder="Search projects" aria-label="Search"
            value="{{ Request::query('q', '') }}">
        <button class="btn btn-outline-primary" type="submit"><i
                class="bi bi-search"></i></button>
    </form>
@endsection
