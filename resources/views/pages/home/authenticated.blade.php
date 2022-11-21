@extends('layouts.app')

@section('content')
    <h2>Your projects</h2>

    @forelse ($projects as $p)
        <p>Project -> {{ $p->name }}</p>
    @empty
        <p>wdefsfeawdfgrgrweawhyfthgrfedfdf</p>
    @endforelse
@endsection
