@extends('layouts.app')

@section('content')
    <form method="@yield('method', 'POST')" action="@yield('action', Request::url())"
        class="m-auto vstack gap-3 needs-validation p-3" style="max-width: 480px"
        novalidate>
        @yield('form')
    </form>
@endsection
