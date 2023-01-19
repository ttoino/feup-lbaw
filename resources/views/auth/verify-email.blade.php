@extends('layouts.app')

@push('main-classes', 'align-items-center justify-content-center flex-column ')

@section('content')
    A confirmation email has been sent to the address you specified.

    <form action="{{ route('verification.send') }}" method="POST">
        @csrf
        <input type="submit" class="btn btn-primary" value="Resend Verification Email">
    </form>
@endsection