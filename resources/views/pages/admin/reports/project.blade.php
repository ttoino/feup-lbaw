@extends('layouts.paginated-list')
@php
    $paginator = $reports;
    $itemView = 'partials.list-item.project-report';
@endphp

@section('empty-list')
    <p>No reports on this project!</p>
@endsection

@section('list-title')
    @include('partials.admin.nav')
@endsection
