@extends('layouts.paginated-list')
@php
    $paginator = $reports;
    $itemView = 'partials.list-item.user-report';
@endphp

@section('empty-list')
    <p>No reports on this user!</p>
@endsection

@section('list-title')
    @include('partials.admin.nav')
@endsection