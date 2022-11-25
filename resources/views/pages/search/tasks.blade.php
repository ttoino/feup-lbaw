@extends('layouts.project')
@php
    $paginator = $tasks;
    $itemView = 'partials.list-item.task';
@endphp

@section('empty-list')
    <div class="vstack align-items-center justify-content-center h-100">
        <p class="display-5">No tasks match the search term!</p>
    </div>
@endsection

@section('list-title')
    <div class="hstack justify-content-between">
        <h2>Search results</h2>
    </div>
@endsection

@section('project-content')
    <section class="flex-column p-3 container gap-3">
        @include('partials.paginated-list')
    </section>
@endsection
