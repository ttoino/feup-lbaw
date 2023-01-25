@extends('layouts.app')

@push('main-classes', 'align-items-center justify-content-center flex-column ')

@section('content')
    <p>A confirmation email has been sent to you.</p>

    <form action="{{ route('verification.send') }}" method="POST">
        @csrf

        <x-button type="submit">
            Send again
        </x-button>
    </form>
@endsection
