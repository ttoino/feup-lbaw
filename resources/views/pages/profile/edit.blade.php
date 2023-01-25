@extends('layouts.centered-form')

@section('title', $user->name)

@section('form')
    <label class="image-input align-self-center">
        <img src="{{ asset($user->profile_pic) }}" width=240 height=240 alt="{{ $user->name }}'s profile picture"
            class="rounded-circle">
        <input class="visually-hidden" type="file" name="profile_picture" accept="image/*" value="">
    </label>

    <div class="form-floating">
        <input aria-describedby="name-feedback" placeholder=""
            class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}" id="name" type="text"
            name="name" value="{{ $user->name }}" required autofocus>
        <label for="name" class="form-label">Name</label>
        <div class="invalid-feedback" id="name-feedback">
            @error('name')
                {{ $message }}
            @else
                Invalid name
            @enderror
        </div>
    </div>

    <div class="form-floating">
        <input aria-describedby="email-feedback" placeholder=""
            class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}" id="email" type="email"
            name="email" value="{{ $user->email }}" readonly disabled>
        <label for="email" class="form-label">E-mail</label>
        <div class="invalid-feedback" id="email-feedback">
            @error('email')
                {{ $message }}
            @else
                Invalid email
            @enderror
        </div>
    </div>

    <button type="submit" class="btn btn-primary">
        Edit
    </button>
@endsection
