@extends('layouts.app')

@push('main-classes', 'align-items-center justify-content-center flex-column ')

@section('content')
    <h2 class="display-2">Error @yield('code')</h2>
    <p class="display-5">@yield('message')</p>
@endsection
