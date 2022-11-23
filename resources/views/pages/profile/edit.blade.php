@extends('layouts.app')

@section('title', $user->name)

@section('content')
<article class="profile" data-user-id="{{ $user->id }}">
  <header>
    <h1>{{ $user->name }}</h1>
    @if (Auth::user()->id == $user->id)
    <div class=container11>
      <div class=profile_image>
        <img src="https://picsum.photos/500" width=500 height=500 alt="Profile Picture">
        <input class="btn btn-primary" type="button" value="Change Profile Picture">
      </div>
      <form id="iprof" method="POST" action>
        @csrf
        <div>Name:<input type="text" class="form-control" name="name" placeholder="{{$user->name}}"></div><br>
        
        <button type="submit" class="btn btn-primary">
          Edit
        </button>
      </form>
    </div>
    @endif
  </header>
</article>
@endsection
