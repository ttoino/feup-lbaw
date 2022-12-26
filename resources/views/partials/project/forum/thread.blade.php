<li class="thread" data-thread-id="{{ $thread->id }}">
    @if ($thread->author !== null)
        <a href="{{ route('user.profile', ['user' => $thread->author]) }}" role="button" style="z-index: 100">
    @endif
    <img width="40" height="40" alt="Profile picture" src="{{ asset($thread->author?->getProfilePicture()) }}"
        class="rounded-circle">
    @if ($thread->author !== null)
        </a>
    @endif

    <div>
        <div>
            <a href="{{ route('project.thread', ['project' => $project, 'thread' => $thread]) }}"
                @class(['fw-bold', 'stretched-link'])>
                {{ $thread->title }}
            </a>
            @include('partials.datediff', [
                'date' => $thread->creation_date,
            ])
        </div>
        <div>
            @if ($thread->author !== null)
                <a href="{{ route('user.profile', ['user' => $thread->author]) }}" role="button" aria-expanded="false"
                    style="z-index: 100">
            @endif
            {{ $thread->author?->name ?? 'Anonymous' }}
            @if ($thread->author !== null)
                </a>
            @endif
            <span>
                {{ $thread->comments->count() }} <i class="bi bi-chat-right-text"></i>
            </span>
        </div>
    </div>
</li>
