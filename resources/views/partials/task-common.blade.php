<div class="vstack gap-1 align-self-center">
    @if (!$task->tags->isEmpty())
        <ul class="hstack list-unstyled gap-1">
            @foreach ($task->tags as $tag)
                <li class="rounded-pill p-1" style="background: rgb({{ $tag->rgbColor() }}); flex: 48px 0 1">
                </li>
            @endforeach
        </ul>
    @endif

    <a class="stretched-link" href="{{ route('project.task.info', ['project' => $project, 'task' => $task]) }}">
        {{ $task->name }}
    </a>

    @if (!$task->assignees->isEmpty())
        <ul class="hstack list-unstyled gap-n1">
            @foreach ($task->assignees as $assignee)
                <li>
                    <a href="{{ route('user.profile', ['user' => $assignee]) }}" @class(['assignee'])>
                        <img src="https://picsum.photos/40" alt="{{ $assignee->name }}" width="24" height="24"
                            class="rounded-circle">
                    </a>
                </li>
            @endforeach
        </ul>
    @endif
</div>
<button class="btn btn-outline" style="z-index: 50">
    <i @class([
        'bi',
        'bi-check-circle' => $task->state !== 'completed',
        'bi-check-circle-fill' => $task->state === 'completed',
    ])></i>
</button>
