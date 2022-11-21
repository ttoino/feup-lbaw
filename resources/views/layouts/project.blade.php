@extends('layouts.app')

@section('content')
    @include('partials.project.drawer')

    @yield('project-content')
@endsection
