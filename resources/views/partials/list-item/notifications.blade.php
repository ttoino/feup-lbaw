<li
    class="list-group-item list-group-item-action position-relative d-flex flex-row align-items-center gap-2">
    <div class="hstack flex-fill justify-content-between">
        @switch ($item->type)
            @case('App\Notifications\ProjectInvite')
                <a href={{ url($item->json['url']) }}>You've been invited to join
                    project
                    <strong>{{ $item->json['project']->name }}</strong>. Click here
                    to
                    join.</a>
            @break

            @case('App\Notifications\ProjectRemoved')
                <a href={{ route('project.list') }}> You were removed from project
                    <strong>{{ $item->json['project']->name }}</strong>. </a>
            @break

            @case('App\Notifications\ProjectArchived')
                <a
                    href={{ route('project.board', ['project' => $item->json['project']]) }}>
                    Project
                    <strong>{{ $item->json['project']->name }}</strong> has been
                    archived.
                </a>
            @break

            @case('App\Notifications\TaskCommented')
                <a
                    href={{ route('project.task.info', ['project' => $item->json['comment']->task->project, 'task' => $item->json['comment']->task]) }}>
                    {{ $item->json['comment']->author->name }} has commented on a
                    task
                    you're
                    assigned to: "{{ $item->json['comment']->content }}" </a>
            @break

            @case('App\Notifications\TaskCompleted')
                <a
                    href={{ route('project.task.info', ['project' => $item->json['task']->project, 'task' => $item->json['task']]) }}>
                    Task <strong>{{ $item->json['task']->name }}</strong> has been
                    completed.
                </a>
            @break

            @case('App\Notifications\ThreadNew')
                <a
                    href={{ route('project.thread', ['project' => $item->json['thread']->project, 'thread' => $item->json['thread']]) }}>
                    A new thread "{{ $item->json['thread']->title }}" has been
                    opened by
                    {{ $item->json['thread']->author->name }} on project
                    <strong>{{ $item->json['thread']->project->name }}</strong>.
                </a>
            @break

            @case('App\Notifications\ThreadCommented')
                <a
                    href={{ route('project.thread', ['project' => $item->json['thread_comment']->thread->project, 'thread' => $item->json['thread_comment']->thread]) }}>
                    {{ $item->json['thread_comment']->author->name }} has commented
                    on
                    your
                    thread {{ $item->json['thread_comment']->thread->title }}:
                    "{{ $item->json['thread_comment']->content }}" </strong>. </a>
            @break
        @endswitch
        <span class='align-self-center'>
            <i class="bi bi-calendar mx-1"></i>
            @include('partials.datediff', [
                'date' => $item->creation_date,
            ])
        </span>
    </div>
</li>
