@extends('layouts.app')

@section('title', $user->name)

@push('main-classes', 'flex-column align-items-center justify-content-center p-2 gap-3')

@section('content')
    <img src="https://picsum.photos/240" width=240 height=240 alt="Profile Picture" class="rounded-circle">

    <h2 class="m-0">{{ $user->name }}</h2>

    <p class="m-0"><a href="mailto:{{ $user->email }}">{{ $user->email }}</a></p>

    @if (Auth::user()->id == $user->id || Auth::user()->is_admin)
        <a href="{{ route('user.edit', ['id' => $user->id]) }}" class="btn btn-primary">
            <i class="bi bi-pencil"></i> Edit profile
        </a>

        <form method="POST" action="{{ route('api.user.delete', ['id' => $user->id]) }}">
            @csrf
            @method('DELETE')
            <button class="btn btn-outline-danger" type="submit">
                <i class="bi bi-trash3"></i> Delete account
            </button>
        </form>
    @endif
@endsection
