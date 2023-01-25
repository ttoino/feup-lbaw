@extends('layouts.centered-form')

@section('title', 'Create new Project')

@section('form')
    <h2 class="text-center h1">Create your new idea!</h2>

    <div class="form-floating">
        <input aria-describedby="name-feedback" placeholder=""
            class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" id="name" type="text" name="name"
            value="{{ old('name') }}" minlength="6" maxlength="512" required autofocus>
        <label for="name" class="form-label">Name</label>
        <div class="invalid-feedback" id="name-feedback">
            Invalid name
        </div>
    </div>

    <div class="form-floating">
        <textarea placeholder="" class="form-control auto-resize {{ $errors->has('description') ? 'is-invalid' : '' }}"
            aria-describedby="description-feedback" id="description" name="description" minlength="6" maxlength="512" required>{{ old('description') }}</textarea>
        <label for="description" class="form-label">Description</label>
        <div class="invalid-feedback" id="description-feedback">
            Invalid description
        </div>
    </div>

    <button type="submit" class="btn btn-primary">
        Create
    </button>
@endsection
