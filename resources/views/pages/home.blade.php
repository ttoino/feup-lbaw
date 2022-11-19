@extends('layouts.app')
@section('title', 'Home Page')

@section('content')
    @auth
        <h2>Your projects:</h2>
        @forelse ($projects as $p)
            <p>Project -> {{ $p->name }}</p>
        @empty
            <p>wdefsfeawdfgrgrweawhyfthgrfedfdf</p>
        @endforelse
    @endauth
    
    @guest
        <div class="container overflow-hidden">
            <div class="row mx-auto w-75 gx-5">
                <div class="col-4 border border-primary">
                    <div class="container h-75 w-75">
                        Boas {{-- Logo --}}
                    </div>
                </div> 
                <div class="col-8 border border-primary px-4">
                    <div class="row">
                        <h1>{{ config('app.name', 'Laravel') }}</h1>
                    </div>
                    <div class="row mt-3">Pequena description of the projeto</div>
                    <div class="row mt-5">
                        <a href="{{ route('register') }}" class="btn btn-outline">Register</a>
                        <a href="{{ route('login') }}" class="btn btn-primary">Login</a>
                    </div>
                </div>
            </div>
        </div>
    @endguest
@endsection
