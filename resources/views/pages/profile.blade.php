@extends('layouts.app')

@section('title', $user->name)

@push('main-classes', 'flex-column align-items-center justify-content-center p-2 gap-3')

@section('content')
    <img src="{{ asset($user->profile_pic) }}" width=240 height=240 alt="Profile Picture" class="rounded-circle">

    <h2 class="m-0">{{ $user->name }}</h2>

    <p class="m-0"><a href="mailto:{{ $user->email }}">{{ $user->email }}</a></p>

    @can('update', $user)
        <x-button type="link" href="{{ route('user.edit', ['user' => $user]) }}" icon="pencil">
            Edit profile
        </x-button>
    @endcan

    @can('report', $user)
        <x-button type="link" href="{{ route('user.report', ['user' => $user]) }}" outline color="danger">
            Report
        </x-button>
    @endcan

    @can('delete', $user)
        <x-button class="delete-user" outline color="danger" icon="trash">
            Delete account
        </x-button>
    @endcan
@endsection
