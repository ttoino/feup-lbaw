@extends('layouts.project')

@push('main-classes', 'overflow-auto ')

@section('project-content')
    <section class="forum-threads">
        @if (Auth::user()?->projects->contains($project))
            <header>
                <a id="new-thread-button"
                    @if ($project->archived) href="#new-thread-offcanvas"
                    aria-disabled="true" @endif
                    role="button" @class(['btn', 'btn-primary', 'disabled' => $project->archived])>
                    <i class="bi bi-plus"></i>New Thread
                </a>
            </header>
        @endif
        <ul>
            @each('partials.project.forum.thread', $project->threads, 'thread')
        </ul>
    </section>

    <aside id="thread-offcanvas" @class([
        'show' => $show_thread ?? false,
        'offcanvas-md',
        'offcanvas-end',
    ])>
        @include('partials.project.thread')
    </aside>

    @if (Auth::user()?->projects->contains($project))
        <aside id="new-thread-offcanvas">
            <header class="offcanvas-header">
                <h2 class="offcanvas-title h4" id="new-thread-offcanvas-title">
                    New thread
                </h2>
                <button type="button" class="btn-close" data-bs-dismiss="offcanvas" data-bs-target="#new-thread-offcanvas"
                    aria-label="Close"></button>
            </header>
            @include('partials.project.forum.new-thread')
        </aside>
    @endif
@endsection
