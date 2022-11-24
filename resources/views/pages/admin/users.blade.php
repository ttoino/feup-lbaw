@extends('layouts.paginated-list')
@php
    $paginator = $users;
    $itemView = 'partials.list-item.user-admin';
@endphp

@section('empty-list')
    <p>No users have been created!</p>
@endsection

@section('list-title')
    <h2>All users</h2>
@endsection
