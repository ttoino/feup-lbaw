@extends('layouts.app')

@push('main-classes', 'flex-column ')

@section('content')
    {{-- TODO: joão não gosta, pensar noutra cena @toino --}}

    @if ($project->archived)
        <div class="alert alert-warning m-0 text-center p-2 rounded-0" role="alert">This project has been archived. It is now read-only.</div>
    @endif

    <div class="d-flex flex-fill overflow-auto">
        @include('partials.project.drawer')
    
        @yield('project-content')
    </div>

@endsection
