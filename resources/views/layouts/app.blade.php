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
                <div class="dropdown">
                    <a href="#" role="button" data-bs-toggle="dropdown"
                        aria-expanded="false" class="position-relative d-block">
                        <img width="40" height="40" alt="Profile picture"
                            src="{{ asset(Auth::user()->profile_pic) }}"
                            class="rounded-circle">
                        @if (Auth::user()->unreadNotifications->count())
                            <span style="padding: .375rem"
                                class="position-absolute top-0 end-0 bg-danger rounded-circle">
                                <span class="visually-hidden">Unread
                                    notifications</span>
                            </span>
                        @endif
                    </a>
                    <nav class="dropdown-menu dropdown-menu-end shadow-sm"
                        style="min-width: 240px">
                        <a href="{{ route('user.profile', ['user' => Auth::user()]) }}"
                            class="dropdown-item hstack gap-2">
                            <i class="bi bi-person-fill"></i> {{ Auth::user()->name }}
                        </a>
                        <a href="{{ route('notifications') }}"
                            class="dropdown-item hstack gap-2">
                            <i class="bi bi-bell-fill"></i> Notifications
                            ({{ Auth::user()->unreadNotifications->count() }})
                        </a>
                        <a href="{{ route('logout') }}"
                            class="dropdown-item text-danger hstack gap-2">
                            <i class="bi bi-box-arrow-right"></i> Logout
                        </a>
                    </nav>
                </div>
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
