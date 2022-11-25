@extends('layouts.with-footer')

@section('above-footer')
    <header class="navbar navbar-expand navbar-dark sticky-top bg-primary shadow">
        <div class="container-fluid">
            @stack('navbar-left')

            <a class="navbar-brand" href="{{ route('home') }}">{{ config('app.name', 'Laravel') }}</a>

            <form method="GET" action="{{ route('project.search') }}" class="input-group ms-auto" role="search"
                style="max-width: 360px">
                <input class="form-control" name="q" type="search" placeholder="Search projects" aria-label="Search"
                    value="{{ Request::route()->getName() === 'project.search' ? Request::query('q', '') : '' }}">
                <button class="btn btn-outline-light" type="submit"><i class="bi bi-search"></i></button>
            </form>

            @auth
                <div class="dropdown ms-3">
                    <a href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <img width="40" height="40" alt="Profile picture" src="https://picsum.photos/40"
                            class="rounded-circle p-1">
                    </a>
                    <nav class="dropdown-menu dropdown-menu-end shadow-sm" style="min-width: 240px">
                        <a href="{{ route('user.profile', ['id' => Auth::user()->id]) }}" class="dropdown-item hstack gap-2">
                            <i class="bi bi-person-fill"></i> {{ Auth::user()->name }}
                        </a>
                        <a href="#" class="dropdown-item hstack gap-2">
                            <i class="bi bi-bell-fill"></i> Notifications
                        </a>
                        <a href="{{ route('logout') }}" class="dropdown-item text-danger hstack gap-2">
                            <i class="bi bi-box-arrow-right"></i> Logout
                        </a>
                    </nav>
                </div>
            @endauth

            @guest
                <nav class="navbar-nav ms-3">
                    <a href="{{ route('login') }}" class="nav-link">Login</a>
                    <a href="{{ route('register') }}" class="nav-link">Register</a>
                </nav>
            @endguest
        </div>
    </header>

    <main class="d-flex flex-fill @stack('main-classes')">
        @yield('content')
    </main>
@endsection
