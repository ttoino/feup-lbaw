@extends('layouts.project')

@push('main-classes', 'overflow-auto ')

@once
    @push('templates')
        <template id="thread-comment-template">
            @include('partials.project.forum.comment')
        </template>
        <template id="thread-template">
            @include('partials.project.forum.thread')
        </template>
    @endpush
@endonce

@section('project-content')
    <section class="forum-threads">
        @if (Auth::user()?->projects->contains($project) && !$project->archived)
            <header>
                <a id="new-thread-button" href="#new-thread-offcanvas" role="button" @class(['btn', 'btn-primary'])>
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
        'loader',
    ])>
        @include('partials.project.thread')
        @include('partials.loading')
    </aside>

    @if (Auth::user()?->projects->contains($project) && !$project->archived)
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
