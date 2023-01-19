@extends('layouts.centered-form')

@section('title', 'Reset password')

@section('form')
    <x-form.textfield type="email" name="email" autocomplete="email" required>
        Email
    </x-form.textfield>

    <x-button type="submit">
        Recover password
    </x-button>
@endsection
