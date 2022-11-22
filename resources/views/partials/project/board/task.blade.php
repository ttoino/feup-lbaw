<li class="shadow-sm rounded p-2 bg-white position-relative hstack justify-content-between" data-task-id="{{ $task->id }}">
    <a class="stretched-link" style="color: inherit; text-decoration: inherit"
        href="{{ route('project.task.info', ['id' => $task->project, 'taskId' => $task->id]) }}">
        {{ $task->name }}
    </a>
    <button class="btn btn-outline" style="z-index: 50">
        <i @class([
            'bi', 
            'bi-heart' => $task->state !== 'completed', 
            'bi-heart-fill' => $task->state === 'completed'
            ])></i>
    </button>
</li>
