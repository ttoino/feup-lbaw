@extends('layouts.centered-form')

@section('title', 'Reset password')

@section('action', route('password.reset-action'))

@section('form')
    <x-form.textfield type="email" name="email" autocomplete="email" value="{{ request()->query('email') }}" required
        autofocus>
        Email
    </x-form.textfield>

    <x-form.textfield type="password" name="password" autocomplete="new-password" required>
        Password
    </x-form.textfield>

    <x-form.textfield type="password" name="password" autocomplete="new-password" required>
        Confirm password
    </x-form.textfield>

    <input type="hidden" name="token" value="{{ $token }}">

    <x-button type="submit">
        Reset password
    </x-button>
@endsection
