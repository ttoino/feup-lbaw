<div @class([
    'shadow-sm',
    'rounded',
    'p-2',
    'd-flex',
    'flex-column',
    'gap-3',
    'mh-100',
    'bg-light',
    'flex-shrink-0',
]) style="width: 270px"
@isset($group)
    data-task-group-id="{{ $group->id }}"
@endisset
>

    @isset($group)
        <div class="hstack">
            <i class="bi bi-grip-horizontal grip" style="cursor: grab"></i>
            <p class="m-0 text-center flex-fill">{{ $group->name }}</p>
        </div>

        <ul class="list-unstyled vstack gap-2 p-2"
            style="overflow-y: auto; margin: -.5rem"
            data-task-group-id="{{ $group->id }}">
            @each ('partials.project.board.task', $group->tasks, 'task')
        </ul>

        <form method="@yield('method', 'POST')" action="{{ route('project.task.new', ['project' => $project]) }}">
            @csrf
            <div class="input-group">
                <input aria-label="Create Task Name" aria-describedby="task-name"
                    class="form-control" id="name" type="text"
                    name="name" placeholder="Create Task" minlength=4 required>
                <button class="btn btn-primary" type="submit"><i
                        class="bi bi-plus"></i></button>
            </div>
            <input type="hidden" class="form-control" id="task_group"
                name="task_group" value="{{ $group->id }}">
        </form>
    @else
        <form method="@yield('method', 'POST')"
            action="{{ route('project.task-group.new', ['project' => $project]) }}">
            @csrf
            <div class="input-group">
                <input aria-label="Create Group Name" aria-describedby="group-name"
                    class="form-control" id="name" type="text"
                    name="name" placeholder="Create Group" minlength=4 required>
                <button class="btn btn-primary" type="submit"><i
                        class="bi bi-plus"></i></button>
            </div>
            <input type="hidden" class="form-control" id="project" name="project"
                value="{{ $project->id }}">
        </form>
    @endisset
</div>
