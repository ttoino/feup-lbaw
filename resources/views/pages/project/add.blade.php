@extends('layouts.centered-form')

@section('title', 'Create new Project')

@section('form')
    @csrf

    <h2 class="text-center h1">Add a user to your project</h2>

    <div class="form-floating">
        <select name="id" required>
            @foreach ($users as $user)
                <option value={{$user->id}}>{{$user->name}}</option>
            @endforeach
        </select>
    </div>

    <button type="submit" class="btn btn-primary">
        Add User
    </button>
@endsection