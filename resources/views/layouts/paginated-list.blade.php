@extends('layouts.app')

@push('main-classes', 'flex-column p-3 container gap-3')

@section('content')
    @if ($paginator->isEmpty())
        @yield('empty-list')
    @else
        @yield('list-title')

        @include('partials.list')

        {{ $paginator->links() }}
    @endif
@endsection
