<li class="shadow-sm rounded p-2 bg-white position-relative">
    <a class="stretched-link" style="color: inherit; text-decoration: inherit"
        href="{{ route('project.task.info', ['id' => $task->project, 'taskId' => $task->id]) }}">
        {{ $task->name }}
    </a>
</li>
