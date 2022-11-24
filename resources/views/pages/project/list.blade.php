@extends('layouts.paginated-list')
@php
    $paginator = $projects;
    $itemView = 'partials.list-item.project';
@endphp

@section('empty-list')
    <p>You don't have any projects yet!</p>
    <a href="{{ route('project.new') }}" class="btn btn-primary"><i
            class="bi bi-plus"></i> Create your first</a>
@endsection

@section('list-title')
    <div class="hstack justify-content-between">
        <h2>Your projects</h2>
        <a href="{{ route('project.new') }}" class="btn btn-primary hstack gap-2">
            <i class="bi bi-plus"></i> Create project
        </a>
    </div>
@endsection
