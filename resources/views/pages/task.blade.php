@extends('layouts.project')

@section('title', $task->name)

@section('project-content')
    <div class="col-10">
        <p>{{$task->name}}</p>
        <p>Description: {{$task->description}}</p>
    </div>
@endsection