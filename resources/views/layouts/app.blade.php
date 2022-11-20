@extends('layouts.with-footer')

@section('above-footer')
    <header class="navbar navbar-expand navbar-dark sticky-top bg-primary shadow">
        <div class="container-fluid">
            <a class="navbar-brand"
                href="{{ url('/') }}">{{ config('app.name', 'Laravel') }}</a>

            <nav class="navbar-nav">
                @auth
                    <a href="#" class="nav-link"><i class="bi-bell-fill"></i></a>
                    <a class="nav-link"
                        href="{{ url('profile') }}">{{ Auth::user()->name }}</a>
                    <a class="nav-link" href="{{ route('logout') }}">Logout</a>
                @endauth
                @guest
                    <a href="{{ route('login') }}" class="nav-link">Login</a>
                    <a href="{{ route('register') }}" class="nav-link">Register</a>
                @endguest
            </nav>
        </div>
    </header>

    <main class="container-fluid row py-4 flex-grow-1">
        @yield('content')
    </main>
@endsection
