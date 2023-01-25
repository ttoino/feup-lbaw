@php($task ??= new \App\Models\Task())

<li class="task" data-task-id="{{ $task->id }}" data-render-attr="id,task-id">
    @can('edit', $project)
        <i class="grip" style="z-index: 50; cursor: grab"></i>
    @endcan

    @include('partials.task-common')
</li>
