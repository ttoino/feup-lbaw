@extends('pages.project.forum')

@section('thread-content')
    <section class="vstack">
        @include('partials.project.forum.thread')
        <ul class="list-unstyled gap-2 p-2" style="overflow-y: auto; margin: 0rem -.5rem">
            @each('partials.project.forum.comment', $thread->comments, 'threadComment')
        </ul>
        <a href="" @class(['btn', 'btn-primary'])>
            <i @class(['bi', 'bi-plus'])></i>New comment
        </a>
    </section>
@endsection