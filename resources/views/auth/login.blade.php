@extends('layouts.centered-form')

@section('title', 'Login')

@section('form')
    @csrf

    <div class="form-floating">
        <input aria-describedby="email-feedback" placeholder=""
            class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}" id="email" type="email" name="email"
            value="{{ old('email') }}" required autofocus>
        <label for="email" class="form-label">E-mail</label>
        <div class="invalid-feedback" id="email-feedback">
            @error('email')
                {{ $message }}
            @else
                Invalid email
            @enderror
        </div>
    </div>

    <div class="form-floating">
        <input placeholder="" class="form-control {{ $errors->has('password') ? 'is-invalid' : '' }}"
            aria-describedby="password-feedback" id="password" type="password" name="password" required>
        <label for="password" class="form-label">Password</label>
        <div class="invalid-feedback" id="password-feedback">
            @error('password')
                {{ $message }}
            @else
                Invalid password
            @enderror
        </div>
    </div>

    <div class="form-check">
        <input type="checkbox" class="form-check-input" name="remember" id="remember"
            {{ old('remember') ? 'checked' : '' }}>
        <label class="form-check-label" for="remember">Remember Me</label>
    </div>

    <button type="submit" class="btn btn-primary">
        Login
    </button>

    <p class="text-center">Don't have an account?
        <a href="{{ route('register') }}" class="link-primary">Register</a>
    </p>
@endsection
