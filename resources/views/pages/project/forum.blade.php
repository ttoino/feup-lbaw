@extends('layouts.project')

@push('main-classes', 'overflow-auto ')

@section('project-content')
    <section class="vstack" style="width: 35%"> {{-- TODO: fix this --}}
        <a href="{{ route('project.thread.new', ['project' => $project]) }}" @class(['btn', 'btn-primary'])>
            <i @class(['bi', 'bi-plus'])></i>New Thread
        </a>
        <ul class="list-unstyled gap-2 p-2" style="overflow-y: auto; margin: 0rem -.5rem">
            @each('partials.project.forum.thread', $project->threads, 'thread')
        </ul>
    </section>
    @yield('thread-content')
@endsection
