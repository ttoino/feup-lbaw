@extends('layouts.centered-form')

@section('title', 'Create User')

@section('form')
    @csrf

    <h2 class="text-center h1">Create a new user</h2>

    <div class="form-floating">
        <input aria-describedby="name-feedback" placeholder=""
            class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}"
            id="name" type="text" name="name" value="{{ old('name') }}"
            required autofocus>
        <label for="name" class="form-label">Name</label>
        <div class="invalid-feedback" id="name-feedback">
            @error('name')
                {{ $message }}
            @else
                Invalid name
            @enderror
        </div>
    </div>

    <div class="form-floating">
        <input aria-describedby="email-feedback" placeholder=""
            class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}"
            id="email" type="email" name="email" value="{{ old('email') }}"
            required>
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
        <input placeholder=""
            class="form-control {{ $errors->has('password') ? 'is-invalid' : '' }}"
            aria-describedby="password-feedback" id="password" type="password"
            name="password" required>
        <label for="password" class="form-label">Password</label>
        <div class="invalid-feedback" id="password-feedback">
            @error('password')
                {{ $message }}
            @else
                Invalid password
            @enderror
        </div>
    </div>

    <div class="form-floating">
        <input placeholder=""
            class="form-control {{ $errors->has('password-confirm') ? 'is-invalid' : '' }}"
            aria-describedby="password-confirm-feedback" id="password-confirm"
            type="password" name="password_confirmation" required>
        <label for="password-confirm" class="form-label">Confirm
            password</label>
        <div class="invalid-feedback" id="password-confirm-feedback">
            @error('password_confirmation')
                {{ $message }}
            @else
                Invalid password confirmation
            @enderror
        </div>
    </div>

    <button type="submit" class="btn btn-primary">
        Create
    </button>

@endsection
