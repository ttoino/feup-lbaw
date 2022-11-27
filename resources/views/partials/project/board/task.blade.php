<li @class([
    'shadow-sm',
    'rounded',
    'p-2',
    'gap-2',
    'bg-white',
    'position-relative',
    'hstack',
    'justify-content-between',
    'text-break',
]) data-task-id="{{ $task->id }}">
    <i class="bi bi-grip-vertical grip" style="z-index: 50; cursor: grab"></i>

    @include('partials.task-common')
</li>
