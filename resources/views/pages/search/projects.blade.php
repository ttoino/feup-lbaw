@extends('layouts.paginated-list')
@php
    $paginator = $projects;
    $itemView = 'partials.list-item.project';
@endphp

@section('empty-list')
    <p>No projects match the search term!</p>
@endsection

@section('list-title')
    <div class="hstack justify-content-between">
        <h2>Search results</h2>
    </div>
@endsection
