@extends('layouts.with-footer')

@section('above-footer')
    <header class="navbar navbar-expand navbar-dark sticky-top bg-primary shadow">
        <div class="container-fluid">
            @stack('navbar-left')

            <a class="navbar-brand"
                href="{{ route('home') }}">{{ config('app.name', 'Laravel') }}</a>

            <nav class="navbar-nav ms-auto">
                @auth
                    <a href="{{ url('project/new') }}" class="nav-link"
                        data-toggle="tooltip" data-placement="left"
                        title="Create new project">Create Project <i
                            class="bi-plus-circle-fill"></i></a>
                    <a href="#" class="nav-link"><i class="bi-bell-fill"></i></a>
                    <a class="nav-link" href="{{ url('profile') }}"
                        data-toggle="tooltip" data-placement="left"
                        title="Go to user profile">{{ Auth::user()->name }}</a>
                    <a class="nav-link" href="{{ route('logout') }}">Logout</a>
                @endauth
                @guest
                    <a href="{{ route('login') }}" class="nav-link">Login</a>
                    <a href="{{ route('register') }}" class="nav-link">Register</a>
                @endguest
            </nav>
        </div>
    </header>

    <main class="d-flex flex-fill @stack('main-classes')">
        @yield('content')
    </main>
@endsection
