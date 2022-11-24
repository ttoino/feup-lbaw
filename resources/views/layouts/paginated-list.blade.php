@extends('layouts.app')

@push('main-classes', 'flex-column p-3 container gap-3')

@section('content')
    @empty($paginator)
        @yield('empty-list')
    @else
        @yield('list-title')

        @include('partials.list')

        {{ $paginator->links() }}
    @endempty
@endsection
