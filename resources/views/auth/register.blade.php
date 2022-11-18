@extends('layouts.app')

@section('content')
    <form method="POST" action="{{ route('register') }}">
        @csrf

        <div class="mb-3">
            <label for="name" class="form-label">Name</label>
            <input aria-describedby="name-feedback"
                class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}"
                id="name" type="text" name="name"
                value="{{ old('name') }}" required autofocus>
            @error('name')
                <div class="invalid-feedback" id="name-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">E-mail</label>
            <input aria-describedby="email-feedback"
                class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}"
                id="email" type="email" name="email"
                value="{{ old('email') }}" required>
            @error('email')
                <div class="invalid-feedback" id="email-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input
                class="form-control {{ $errors->has('password') ? 'is-invalid' : '' }}"
                aria-describedby="password-feedback" id="password" type="password"
                name="password" required>
            @error('password')
                <div class="invalid-feedback" id="password-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="password-confirm" class="form-label">Confirm
                password</label>
            <input
                class="form-control {{ $errors->has('password-confirm') ? 'is-invalid' : '' }}"
                aria-describedby="password-confirm-feedback" id="password-confirm"
                type="password" name="password_confirmation" required>
            @error('password-confirm')
                <div class="invalid-feedback" id="password-confirm-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary">
            Register
        </button>
        <a class="btn btn-outline" href="{{ route('login') }}">Login</a>
    </form>
@endsection
