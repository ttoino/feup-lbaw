@extends('layouts.app')

@push('main-classes', 'flex-column p-3 container gap-3')

@section('content')
    @include('partials.paginated-list')
@endsection
