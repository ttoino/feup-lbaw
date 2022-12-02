@extends('layouts.bare')

@section('body')
    @yield('above-footer')

    <footer class="bd-footer bg-light">
        <nav class="nav justify-content-center">
            <a class="nav-link" href="{{ url('about') }}">About us</a>
            <a class="nav-link" href="{{ url('faq') }}">FQA</a>
            <a class="nav-link" href="{{ url('contacts') }}">Contacts</a>
            <a class="nav-link" href="{{ url('services') }}">Services</a>
        </nav>
    </footer>
@endsection
