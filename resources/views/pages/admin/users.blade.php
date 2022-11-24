@extends('layouts.paginated-list')
@php
    $paginator = $users;
    $itemView = 'partials.list-item.user';
@endphp

@section('empty-list')
    <p>No users have been created!</p>
@endsection

@section('list-title')
    @include('partials.admin.nav')
@endsection
