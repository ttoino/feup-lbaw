@extends('layouts.app')
@section('title', $project->name)

@section('content')
    <div class='row'>
        @include('partials.sidemenu', ['project' => $project])
        <div class="col-10">
            
        </div>
    </div>
@endsection
