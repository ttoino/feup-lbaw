@extends('layouts.app')

@section('title', $user->name)

@push('main-classes',
    'flex-column align-items-center justify-content-center p-2
    gap-3')

    @section('content')

        <img src="{{ asset($user->profile_pic) }}" width=240 height=240
            alt="Profile Picture" class="rounded-circle">

        <h2 class="m-0">{{ $user->name }}</h2>

        <p class="m-0"><a href="mailto:{{ $user->email }}">{{ $user->email }}</a></p>

        @can('update', $user)
            <a href="{{ route('user.edit', ['user' => $user]) }}" class="btn btn-primary">
                <i class="bi bi-pencil"></i> Edit profile
            </a>
        @endcan

        @can('report', $user)
            <a href="{{ route('user.report', ['user' => $user]) }}"
                class="btn btn-outline-danger">
                Report User
            </a>
        @endcan

        @can('delete', $user)
            <button class="btn btn-outline-danger" data-bs-toggle="modal"
                data-bs-target="#delete-modal">
                <i class="bi bi-trash3"></i> Delete account
            </button>
        @endcan
    @endsection
