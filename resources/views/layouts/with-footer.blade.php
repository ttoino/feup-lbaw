@extends('layouts.bare')

@section('body')
    @yield('above-footer')

    <footer id="footer">
        <nav>
            <a href="{{ url('about') }}">About us</a>
            <a href="{{ url('faq') }}">FAQ</a>
            <a href="{{ url('contacts') }}">Contacts</a>
            <a href="{{ url('services') }}">Services</a>
        </nav>
    </footer>
@endsection
