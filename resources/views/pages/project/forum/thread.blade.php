@extends('pages.project.forum')

@section('forum-content')
    <article class="thread-content">
        <h2 class="h1">
            {{ $thread->title }}
        </h2>
        @if ($thread->edit_date !== null)
            <span>Edited</span>
        @endif
        <x-markdown>{!! $thread->content !!}</x-markdown>
        <div class="hstack justify-content-between align-items-center">
            <a href="{{ route('user.profile', ['user' => $thread->author]) }}"
                role="button" aria-expanded="false" style="z-index: 100"
                class="hstack justify-content-around">
                <img width="40" height="40" alt="Profile picture"
                    src="{{ asset($thread->author->getProfilePicture()) }}"
                    class="rounded-circle p-1">
                <span>{{ $thread->author->name }}</span>
            </a>

            <span>
                <i class="bi bi-calendar mx-1"></i>
                @include('partials.datediff', [
                    'date' => $thread->creation_date,
                ])
            </span>
        </div>
    </article>
    <ul class="thread-comments">
        @each('partials.project.forum.comment', $thread->comments, 'threadComment')
    </ul>
    <a href="" @class(['btn', 'btn-primary'])>
        <i @class(['bi', 'bi-plus'])></i>New comment
    </a>
@endsection
