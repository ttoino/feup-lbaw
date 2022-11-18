@extends('layouts.app')
@section('title', $project->name)

@section('content')
  @include('partials.sidemenu', ['project' => $project])
@endsection
