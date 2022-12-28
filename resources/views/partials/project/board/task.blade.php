@php($task ??= new \App\Models\Task())

<li class="task" data-task-id="{{ $task->id }}" data-render-attr="id,task-id">
    @if (!$project?->archived)
        <i class="grip" style="z-index: 50; cursor: grab"></i>
    @endif

    @include('partials.task-common')
</li>
