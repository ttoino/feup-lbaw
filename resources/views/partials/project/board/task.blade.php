<li @class([
    'shadow-sm',
    'rounded',
    'p-2',
    'bg-white',
    'position-relative',
    'hstack',
    'justify-content-between',
    'text-break',
]) data-task-id="{{ $task->id }}">
    @include('partials.task-common')
</li>
