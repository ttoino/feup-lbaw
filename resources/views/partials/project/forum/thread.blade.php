<li class="thread" data-thread-id="{{ $thread->id }}">
    <a href="{{ route('user.profile', ['user' => $thread->author]) }}"
        role="button" style="z-index: 100">
        <img width="40" height="40" alt="Profile picture"
            src="{{ asset($thread->author->getProfilePicture()) }}"
            class="rounded-circle">
    </a>

    <div>
        <div>
            <a href="{{ route('project.thread', ['project' => $project, 'thread' => $thread]) }}"
                @class(['fw-bold', 'stretched-link'])>
                {{ $thread->title }}
            </a>
            @if ($thread->edit_date !== null)
                <span>
                    Edited
                </span>
            @endif
        </div>
        <div>
            <a href="{{ route('user.profile', ['user' => $thread->author]) }}"
                role="button" aria-expanded="false" style="z-index: 100">
                {{ $thread->author->name }}
            </a>
            <span>
                {{ $thread->comments->count() }} <i
                    class="bi bi-chat-right-text"></i>
            </span>
        </div>
    </div>
</li>
