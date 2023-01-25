@extends('layouts.paginated-list')
@php
    $paginator = $notifications;
    $itemView = 'partials.list-item.notifications';
@endphp

@section('empty-list')
    <div class="vstack align-items-center justify-content-center">
        <p class="display-6">You don't have any notifications yet!</p>
    </div>
@endsection
