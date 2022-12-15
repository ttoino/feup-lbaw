@extends('layouts.project')

@push('main-classes', 'overflow-auto ')

@section('project-content')
    <section class="forum-threads">
        <header>
            <a href="{{ route('project.thread.new', ['project' => $project]) }}"
                class="btn btn-primary">
                <i class="bi bi-plus"></i>New Thread
            </a>
        </header>
        <ul>
            @each('partials.project.forum.thread', $project->threads, 'thread')
        </ul>
    </section>
    <section class="forum-content">
        @yield('forum-content')
    </section>
@endsection
