@extends('layouts.centered-form')

@section('title', 'Login')

@section('form')

    <x-form.textfield type="email" name="email" autocomplete="email" required autofocus>
        Email
    </x-form.textfield>
    <x-form.textfield type="password" name="password" autocomplete="current-password" required>
        Password
    </x-form.textfield>

    <x-form.check name="remember">Remember me</x-form.check>

    <x-button type="submit">
        Login
    </x-button>

    <x-button.gh />
    <x-button.google />

    <div class="text-center">
        Don't have an account?
        <a href="{{ route('register') }}" class="link-primary">Register</a>
    </div>

    <div class="text-center">
        Forgot your password?
        <a href="{{ route('password.request') }}" class="link-primary">Reset it</a>
    </div>
@endsection
