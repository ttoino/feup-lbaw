@extends('layouts.with-footer')

@section('above-footer')
    <header id="navbar">
        <div class="container-fluid">
            @stack('navbar-left')

            <a class="navbar-brand" href="{{ route('home') }}">
                <img src="{{ asset('logo.svg') }}" alt="Atrellado logo" width="40"
                    height="40">
                <h1 class="h4 m-0">
                    {{ config('app.name', 'Laravel') }}
                </h1>
            </a>

            @auth
                <form method="GET" action="{{ route('project.search') }}"
                    class="input-group ms-auto d-none d-sm-flex" role="search"
                    style="max-width: 360px">
                    <input class="form-control" name="q" type="search"
                        placeholder="Search projects" aria-label="Search"
                        value="{{ Request::route()->getName() === 'project.search' ? Request::query('q', '') : '' }}">
                    <button class="btn btn-outline-light" type="submit"><i
                            class="bi bi-search"></i></button>
                </form>

                <button class="btn btn-outline-light d-block d-sm-none ms-auto"
                    type="button" data-bs-toggle="collapse"
                    data-bs-target="#searchbar-collapse" aria-controls="drawer"
                    aria-expanded="false" aria-label="Toggle project drawer">
                    <i class="bi bi-search"></i>
                </button>

                <div class="dropdown">
                    <a href="#" role="button" data-bs-toggle="dropdown"
                        aria-expanded="false">
                        <img width="40" height="40" alt="Profile picture"
                            src="{{ asset(Auth::user()->profile_pic) }}"
                            class="rounded-circle">
                    </a>
                    <nav class="dropdown-menu dropdown-menu-end shadow-sm"
                        style="min-width: 240px">
                        <a href="{{ route('user.profile', ['user' => Auth::user()]) }}"
                            class="dropdown-item hstack gap-2">
                            <i class="bi bi-person-fill"></i> {{ Auth::user()->name }}
                        </a>
                        <a href="#" class="dropdown-item hstack gap-2">
                            <i class="bi bi-bell-fill"></i> Notifications
                        </a>
                        <a href="{{ route('logout') }}"
                            class="dropdown-item text-danger hstack gap-2">
                            <i class="bi bi-box-arrow-right"></i> Logout
                        </a>
                    </nav>
                </div>

                <form method="GET" action="{{ route('project.search') }}"
                    id="searchbar-collapse" role="search"
                    class="input-group collapse navbar-collapse d-flex d-sm-none">
                    <input class="form-control" name="q" type="search"
                        placeholder="Search projects" aria-label="Search"
                        value="{{ Request::route()->getName() === 'project.search' ? Request::query('q', '') : '' }}">
                    <button class="btn btn-outline-light" type="submit"><i
                            class="bi bi-search"></i></button>
                </form>
            @endauth

            @guest
                <nav class="navbar-nav flex-row">
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
