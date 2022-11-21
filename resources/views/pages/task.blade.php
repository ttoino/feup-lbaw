@extends('layouts.app')
@section('title', $task->name)

@section('content')
<div class='row'>
    @include('partials.sidemenu')
    <div class="col-10">
        <p>{{$task->name}}</p>
        <p>Description: {{$task->description}}</p>
    </div>
</div>
@endsection