@extends('layouts.app')

@section('content')
    <form method="@yield('method', 'POST')" action="@yield('action', Request::url())" class="m-auto"
        style="max-width: 480px">
        @yield('form')
    </form>
@endsection
