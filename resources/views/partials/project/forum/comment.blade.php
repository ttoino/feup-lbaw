<li class="thread-comment" data-thread-comment-id="{{ $threadComment->id }}">
    <article class="vstack">
        <header class="hstack justify-content-between align-items-center">
            <a href="{{ route('user.profile', ['user' => $threadComment->author]) }}"
                role="button" aria-expanded="false" style="z-index: 100"
                class="hstack gap-2">
                <img width="24" height="24" alt="Profile picture"
                    src="{{ asset($threadComment->author->getProfilePicture()) }}"
                    class="rounded-circle">
                <span>{{ $threadComment->author->name }}</span>
            </a>

            <span>
                <i class="bi bi-calendar mx-1"></i>
                @include('partials.datediff', [
                    'date' => $threadComment->creation_date,
                ])
            </span>
        </header>
        <x-markdown>{{ $threadComment->content }}</x-markdown>
    </article>

</li>
