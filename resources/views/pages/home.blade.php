@extends('layouts.with-footer')

@section('above-footer')
    <main @class([
        'd-flex',
        'align-items-center',
        'justify-content-center',
        'flex-fill',
        'text-bg-primary',
        'p-4',
    ])>
        <div class="d-flex gap-4 align-items-center">
            <img width="200px" height="200px" src="https://picsum.photos/200"
                alt="{{ config('app.name', 'Laravel') }} logo"
                class="d-none d-md-block">

            <div class="vstack text-center text-md-start" style="max-width: 40ch">
                <h1 class="display-1">
                    <img width="200" height="200"
                        src="https://picsum.photos/200"
                        alt="{{ config('app.name', 'Laravel') }} logo"
                        class="d-md-none w-auto align-top" style="height: 1.2em">
                    {{ config('app.name', 'Laravel') }}
                </h1>

                <p class="lead">
                    Sign in or create an account to start
                    managing your projects with us!
                </p>
                <div
                    class="hstack gap-4 mb-3 justify-content-center justify-content-md-start">
                    <a href="{{ route('register') }}"
                        class="btn btn-light">Register</a>
                    <a href="{{ route('login') }}"
                        class="btn btn-outline-light">Login</a>
                </div>
            </div>
        </div>
    </main>
@endsection
