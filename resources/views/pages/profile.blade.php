@extends('layouts.app')

@section('title', $user->name)

@section('content')
  @include('partials.profile', ['user' => $user])

@endsection
