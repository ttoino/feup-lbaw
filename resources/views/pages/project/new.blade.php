@extends('layouts.centered-form')

@section('title', 'Create new Project')

@section('form')
    @csrf

    {{-- Should allow for pictures --}}

    <div class="form-floating">
        <input aria-describedby="name-feedback" placeholder=""
            class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}"
            id="name" type="text" name="name" value="{{ old('name') }}"
            required autofocus>
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
        <input placeholder=""
            class="form-control {{ $errors->has('description') ? 'is-invalid' : '' }}"
            aria-describedby="description-feedback" id="description"
            type="password" name="description" required>
        <label for="description" class="form-label">Description</label>
        <div class="invalid-feedback" id="description-feedback">
            @error('description')
                {{ $message }}
            @else
                Invalid descrioption
            @enderror
        </div>
    </div>

    <button type="submit" class="btn btn-primary">
        Create
    </button>
@endsection
