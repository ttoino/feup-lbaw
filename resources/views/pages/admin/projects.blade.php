@extends('layouts.paginated-list')
@php
    $paginator = $projects;
    $itemView = 'partials.list-item.project-admin';
@endphp

@section('empty-list')
    <p>No projects have been created!</p>
@endsection

@section('list-title')
    @include('partials.admin.nav')
@endsection
