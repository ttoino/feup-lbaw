@extends('layouts.paginated-list')
@php
    $paginator = $projects;
    $itemView = 'partials.list-item.project';
@endphp

@section('empty-list')
    <div class="vstack align-items-center justify-content-center">
        <p class="display-5">No projects match the search term!</p>
    </div>
@endsection

@section('list-title')
    <div class="hstack justify-content-between">
        <h2>Search results</h2>
    </div>
@endsection
