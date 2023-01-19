@extends('layouts.centered-form')

@section('title', 'Register')

@section('form')
    <x-form.textfield name="name" autocomplete="username"
        minlength=6 maxlength=255 required autofocus>
        Name
    </x-form.textfield>

    <x-form.textfield type="email" name="email" autocomplete="email" required>
        Email
    </x-form.textfield>

    <x-form.textfield type="password" name="password" autocomplete="new-password" required>
        Password
    </x-form.textfield>

    <x-form.textfield type="password" name="password" autocomplete="new-password" required>
        Confirm password
    </x-form.textfield>

    <x-button type="submit">
        Register
    </x-button>

    <x-button.gh/>
    <x-button.google/>

    <div class="text-center">Already have an account?
        <a class="link-primary" href="{{ route('login') }}">Login</a>
    </div>
@endsection
