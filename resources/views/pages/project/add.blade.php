@extends('layouts.project')

@section('title', 'Create new Project')

@section('form')
    @csrf

    <h2 class="text-center h1">Invite a user!</h2>

    <div class="form-floating">
        <input aria-describedby="email-feedback" placeholder=""
            class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}" id="email" type="email" name="email"
            value="{{ old('email') }}" required autofocus>
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
        Add User
    </button>
@endsection

@section('project-content')
    @include('partials.centered-form')
@endsection
