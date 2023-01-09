@extends('layouts.centered-form')

@section('title', 'Reset password')

@section('action', route('password.reset-action'))

@section('form')
    <div class="form-floating">
        <input aria-describedby="email-feedback" placeholder=""
            class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}" id="email" type="email" name="email"
            value="{{ old('email') }}" required>
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
        <input placeholder="" class="form-control {{ $errors->has('new-password') ? 'is-invalid' : '' }}"
            aria-describedby="new-password-feedback" id="new-password" type="password" name="password" required>
        <label for="new-password" class="form-label">New Password</label>
        <button type="button" class="btn btn-outline-primary"><i class="bi"></i></button>
        <div class="invalid-feedback" id="new-password-feedback">
            @error('new-password')
                {{ $message }}
            @else
                Invalid new password
            @enderror
        </div>
    </div>


    <div class="form-floating input-group has-validation password-input">
        <input placeholder="" class="form-control {{ $errors->has('password-confirm') ? 'is-invalid' : '' }}"
            aria-describedby="password-confirm-feedback" id="password-confirm" type="password" name="password_confirmation"
            required>
        <label for="password-confirm" class="form-label">Confirm
            new password</label>
        <button type="button" class="btn btn-outline-primary"><i class="bi"></i></button>
        <div class="invalid-feedback" id="password-confirm-feedback">
            @error('password_confirmation')
                {{ $message }}
            @else
                Invalid password confirmation
            @enderror
        </div>
    </div>

    <input type="hidden" name="token" value="{{ $token }}">

    <button type="submit" class="btn btn-primary">
        Reset password
    </button>

    <p class="text-center">Already have an account?
        <a class="link-primary" href="{{ route('login') }}">Login</a>
    </p>
@endsection
