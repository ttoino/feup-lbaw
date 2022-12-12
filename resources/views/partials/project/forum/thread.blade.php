<li class="list-group-item list-group-item-action position-relative d-flex flex-row align-items-center gap-2 border p-2"
    data-thread-id="{{ $thread->id }}">
    <a href="{{ route('user.profile', ['user' => $thread->author]) }}" role="button" aria-expanded="false"
        style="z-index: 100">
        <img width="40" height="40" alt="Profile picture" src="{{ asset($thread->author->getProfilePicture()) }}"
            class="rounded-circle p-1">
    </a>

    <div class="vstack flex-fill align-self-center align-items-start">
        <div class="hstack gap-2 mb-3 justify-content-center justify-content-md-start">
            <a href="{{ route('project.thread', ['project' => $project, 'thread' => $thread]) }}" @class(['fw-bold', 'stretched-link'])>
                {{ $thread->title }}
            </a>
            @if ($thread->edit_date !== null)
                Edited
            @endif
        </div>
        <a href="{{ route('user.profile', ['user' => $thread->author]) }}" role="button" aria-expanded="false"
            style="z-index: 100">
            {{ $thread->author->name }}
        </a>
    </div>
</li>
