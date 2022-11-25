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
    <div class="hstack justify-content-between">
        <h2>Your projects</h2>
        <a href="{{ route('project.new') }}" class="btn btn-primary hstack gap-2">
            <i class="bi bi-plus"></i> Create project
        </a>
    </div>
@endsection
