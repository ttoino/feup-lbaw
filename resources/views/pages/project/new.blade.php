@extends('layouts.centered-form')

@section('title', 'Create new Project')

@section('form')
    @csrf

    <h2 class="text-center h1">Create your new idea!</h2>

    {{-- Should allow for pictures --}}

    <div class="form-floating">
        <input aria-describedby="name-feedback" placeholder=""
            class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" id="name" type="text" name="name"
            value="{{ old('name') }}" required autofocus>
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
        <textarea placeholder="" style="height: 240px"
            class="form-control {{ $errors->has('description') ? 'is-invalid' : '' }}" aria-describedby="description-feedback"
            id="description" name="description" required></textarea>
        <label for="description" class="form-label">Description</label>
        <div class="invalid-feedback" id="description-feedback">
            @error('description')
                {{ $message }}
            @else
                Invalid description
            @enderror
        </div>
    </div>

    <button type="submit" class="btn btn-primary">
        Create
    </button>
@endsection
