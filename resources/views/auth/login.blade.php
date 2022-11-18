@extends('layouts.app')

@section('content')
    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div class="mb-3">
            <label for="email" class="form-label">E-mail</label>
            <input aria-describedby="email-feedback"
                class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}"
                id="email" type="email" name="email"
                value="{{ old('email') }}" required autofocus>
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

        <div class="mb-3 form-check">
            <input type="checkbox" class="form-check-input" name="remember"
                id="remember" {{ old('remember') ? 'checked' : '' }}>
            <label class="form-check-label" for="remember">Remember Me</label>
        </div>

        <button type="submit" class="btn btn-primary">
            Login
        </button>
        <a class="btn btn-outline" href="{{ route('register') }}">Register</a>
    </form>
@endsection
