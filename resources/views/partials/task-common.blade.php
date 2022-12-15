<div class="vstack gap-1 align-self-center">
    @if (!$task->tags->isEmpty())
        <ul class="tags">
            @foreach ($task->tags as $tag)
                <li style="background: rgb({{ $tag->rgbColor() }})">
                </li>
            @endforeach
        </ul>
    @endif

    <a class="stretched-link"
        href="{{ route('project.task.info', ['project' => $project, 'task' => $task]) }}">
        {{ $task->name }}
    </a>

    @if (!$task->assignees->isEmpty())
        <ul class="assignees">
            @foreach ($task->assignees as $assignee)
                <li>
                    <a href="{{ route('user.profile', ['user' => $assignee]) }}">
                        <img src="{{ asset($assignee->getProfilePicture()) }}"
                            alt="{{ $assignee->name }}" width="24"
                            height="24" class="rounded-circle">
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
