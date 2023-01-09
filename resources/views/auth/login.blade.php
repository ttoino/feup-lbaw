@extends('layouts.centered-form')

@section('title', 'Login')

@section('form')
    <div class="form-floating">
        <input aria-describedby="email-feedback" placeholder=""
            @class([
                'form-control',
                'is-invalid' => $errors->has('email')
            ])
            id="email" type="email" name="email" value="{{ old('email') }}"
            required autofocus>
        <label for="email" class="form-label">E-mail</label>
        <div class="invalid-feedback" id="email-feedback">
            @error('email')
                {{ $message }}
            @else
                Invalid email
            @enderror
        </div>
    </div>

    <div class="form-floating input-group has-validation password-input">
        <input placeholder=""
            @class([
                'form-control',
                'is-invalid' => $errors->has('password')
            ])
            aria-describedby="password-feedback" id="password" type="password"
            name="password" required>
        <label for="password" class="form-label">Password</label>
        <button type="button" class="btn btn-outline-primary"><i
                class="bi"></i></button>
        <div class="invalid-feedback" id="password-feedback">
            @error('password')
                {{ $message }}
            @else
                Invalid password
            @enderror
        </div>
    </div>

    <div class="form-check">
        <input type="checkbox" class="form-check-input" name="remember"
            id="remember" {{ old('remember') ? 'checked' : '' }}>
        <label class="form-check-label" for="remember">Remember Me</label>
    </div>

    <button type="submit" class="btn btn-primary">
        Login
    </button>

    <p class="text-center">Don't have an account?
        <a href="{{ route('register') }}" class="link-primary">Register</a>
    </p>

    <p class="text-center">Forgot your password?
        <a href="{{ route('password.request') }}" class="link-primary">Click here to reset it</a>
    </p>
@endsection
