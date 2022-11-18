@extends('layouts.app')

@section('content')
    <form method="POST" action="{{ route('register') }}">
        {{ csrf_field() }}

        <div class="mb-3">
            <label for="name" class="form-label">Name</label>
            <input aria-describedby="name-feedback"
                class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}"
                id="name" type="text" name="name"
                value="{{ old('name') }}" required autofocus>
            @if ($errors->has('name'))
                <div class="invalid-feedback" id="name-feedback">
                    {{ $errors->first('name') }}
                </div>
            @endif
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">E-mail</label>
            <input aria-describedby="email-feedback"
                class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}"
                id="email" type="email" name="email"
                value="{{ old('email') }}" required>
            @if ($errors->has('email'))
                <div class="invalid-feedback" id="email-feedback">
                    {{ $errors->first('email') }}
                </div>
            @endif
        </div>

        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input
                class="form-control {{ $errors->has('password') ? 'is-invalid' : '' }}"
                aria-describedby="password-feedback" id="password" type="password"
                name="password" required>
            @if ($errors->has('password'))
                <div class="invalid-feedback" id="password-feedback">
                    {{ $errors->first('password') }}
                </div>
            @endif
        </div>

        <div class="mb-3">
            <label for="password-confirm" class="form-label">Confirm
                password</label>
            <input
                class="form-control {{ $errors->has('password-confirm') ? 'is-invalid' : '' }}"
                aria-describedby="password-confirm-feedback" id="password-confirm"
                type="password" name="password-confirm" required>
            @if ($errors->has('password-confirm'))
                <div class="invalid-feedback" id="password-confirm-feedback">
                    {{ $errors->first('password-confirm') }}
                </div>
            @endif
        </div>

        <button type="submit" class="btn btn-primary">
            Register
        </button>
        <a class="btn btn-outline" href="{{ route('login') }}">Login</a>
    </form>
@endsection
