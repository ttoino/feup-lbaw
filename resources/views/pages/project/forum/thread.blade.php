@extends('pages.project.forum')

@section('thread-content')
    <section class="vstack">
        <article>
            <span class="hstack">
                <h4 class="display-4">
                    {{ $thread->title }}
                </h4>
                @if ($thread->edit_date !== null)
                    <span>Edited</span>
                @endif
            </span>
            <p>{{ $thread->content }}</p>
            <section class="hstack justify-content-between px-5 py-2">
                <a href="{{ route('user.profile', ['user' => $thread->author]) }}" role="button" aria-expanded="false"
                    style="z-index: 100" class="hstack justify-content-around">
                    <img width="40" height="40" alt="Profile picture"
                        src="{{ asset($thread->author->getProfilePicture()) }}" class="rounded-circle p-1">
                    <span>{{ $thread->author->name }}</span>
                </a>

                @php
                    use Carbon\Carbon;
                    
                    $creation_date = Carbon::parse($thread->creation_date);
                @endphp
                <p><i class="bi bi-calendar mx-1"></i>Created
                    {{ $creation_date->diffForHumans(['aUnit' => true]) }}</p>
            </section>
        </article>
        <ul class="list-unstyled gap-2 p-2" style="overflow-y: auto; margin: 0rem -.5rem">
            @each('partials.project.forum.comment', $thread->comments, 'threadComment')
        </ul>
        <a href="" @class(['btn', 'btn-primary'])>
            <i @class(['bi', 'bi-plus'])></i>New comment
        </a>
    </section>
@endsection
