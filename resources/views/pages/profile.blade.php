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
        
        <button class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#delete-modal">
            <i class="bi bi-trash3"></i> Delete account
        </button>

        <div class="modal fade" id="delete-modal" tabindex="-1" aria-labelledby="delete-modal-label" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content modal-body gap-3 align-items-center">
                    <h3 class="modal-title fs-5" id="delete-modal-label">
                        Are you sure you want to delete {{$user->name}}?
                    </h3>
                    <div class="hstack gap-2 align-self-center">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>

                        <form method="POST" action="{{ route('api.user.delete', ['id' => $user->id]) }}" class="d-contents">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-danger" type="submit">
                                Delete
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
          
    @endif
@endsection
