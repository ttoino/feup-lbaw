<li data-notification-id="{{ $item->id }}"
    class="list-group-item list-group-item-action position-relative hstack gap-2">
    <div class="vstack flex-fill">
        @switch ($item->type)
            @case('App\Notifications\ProjectInvite')
                <a class="stretched-link" href={{ url($item->json['url']) }}>You've been
                    invited to join
                    <strong>{{ $item->json['project']?->name ?? 'Deleted project' }}</strong>
                    <br> Click here
                    to join.</a>
            @break

            @case('App\Notifications\ProjectRemoved')
                <a class="stretched-link" href={{ route('project.list') }}>You were
                    removed from
                    <strong>{{ $item->json['project']?->name ?? 'Deleted project' }}</strong>
                </a>
            @break

            @case('App\Notifications\ProjectArchived')
                <a class="stretched-link"
                    href={{ route('project', ['project' => $item->json['project'] ?? 0]) }}>
                    <strong>{{ $item->json['project']->name ?? 'Deleted project' }}</strong>
                    has been archived.
                </a>
            @break

            @case('App\Notifications\ProjectDeleted')
                <span href={{ route('project.list') }}>
                    <strong>{{ $item->json['project_name'] }}</strong> has been deleted.
                </span>
            @break

            @case('App\Notifications\TaskCommented')
                <a class="stretched-link"
                    href={{ route('project.task.info', ['project' => $item->json['comment']?->task->project ?? 0, 'task' => $item->json['comment']?->task ?? 0]) }}>
                    There's a new comment on a task you're assigned to
                </a>
            @break

            @case('App\Notifications\TaskCompleted')
                <a class="stretched-link"
                    href={{ route('project.task.info', ['project' => $item->json['task']?->project ?? 0, 'task' => $item->json['task'] ?? 0]) }}>
                    A task you're assigned to has been completed
                </a>
            @break

            @case('App\Notifications\ThreadNew')
                <a class="stretched-link"
                    href={{ route('project.thread', ['project' => $item->json['thread']?->project ?? 0, 'thread' => $item->json['thread'] ?? 0]) }}>
                    A new thread has been opened in
                    <strong>{{ $item->json['thread']?->project->name ?? 'Deleted project' }}</strong>.
                </a>
            @break

            @case('App\Notifications\ThreadCommented')
                <a class="stretched-link"
                    href={{ route('project.thread', ['project' => $item->json['thread_comment']?->thread->project ?? 0, 'thread' => $item->json['thread_comment']?->thread ?? 0]) }}>
                    Theres a new comment on your thread
                </a>
            @break
        @endswitch
        <time datetime="{{ $item->creation_date['iso'] }}">
            {{ $item->creation_date['long_diff'] }}
        </time>
    </div>
    <button type="button" style="z-index: 100"
        class="read-notification-button btn btn-outline-primary">
        <i class="bi bi-check-lg"></i>
        Mark as read
    </button>
</li>
