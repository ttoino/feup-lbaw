@extends('layouts.paginated-list')
@php
    $paginator = $tasks;
    $itemView = 'partials.list-item.task';
@endphp

@section('empty-list')
    <p>No tasks match the search term!</p>
@endsection

@section('list-title')
    <div class="hstack justify-content-between">
        <h2>Search results</h2>
    </div>
@endsection