@extends('layouts.centered-form')

@section('title', 'Reset password')

@section('form')
    <div class="form-floating">
        <input placeholder="" @class(['form-control', 'is-invalid' => $errors->has('email')]) id="email" type="email" name="email"
            value="{{ old('email') }}" aria-describedby="email-feedback" required autofocus>
        <label for="email" class="form-label">E-mail</label>
        <div class="invalid-feedback" id="email-feedback">
            @error('email')
                {{ $message }}
            @else
                Invalid email
            @enderror
        </div>
    </div>

    <button type="submit" class="btn btn-primary">
        Send password recovery link
    </button>

    <p class="text-center">Don't have an account?
        <a href="{{ route('register') }}" class="link-primary">Register</a>
    </p>

    <p class="text-center">Already have an account?
        <a href="{{ route('login') }}" class="link-primary">Login</a>
    </p>

@endsection
