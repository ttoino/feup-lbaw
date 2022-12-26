<li class="task" data-task-id="{{ $task->id }}">

    @if (!$task->project->archived)
        <i class="grip" style="z-index: 50; cursor: grab"></i>
    @endif

    @include('partials.task-common')
</li>
