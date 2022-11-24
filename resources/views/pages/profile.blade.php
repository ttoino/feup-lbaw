@extends('layouts.app')

@section('title', $user->name)

@section('content')
    <article class="profile" data-user-id="{{ $user->id }}">
        <header>
            <h1>{{ $user->name }}</h1>

            @if ((Auth::user()->id == $user->id) || Auth::user()->is_admin)
                <div class=container11>
                    <div class=profile_image>
                        <img src="https://picsum.photos/500" width=500 height=500 alt="Profile Picture">
                    </div>
                    <div>
                        <li>Name: {{ $user->name }}</li>
                        <li>Email: {{ $user->email }}</li>
                        <a href="{{ route('user.edit', ['id' => $user->id]) }}" class="btn btn-primary">Edit Profile</a>
                    </div>
                    <div>
                        <form method="POST" action>
                            @csrf

                            @method('DELETE')
                            
                            <button type="submit" class="btn btn-primary">
                                Delete User
                            </button>
                        </form>
                    </div>
                </div>
            @endif
        </header>
    </article>
@endsection
