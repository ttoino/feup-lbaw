<div class="task-group"
    @isset($group)
    data-task-group-id="{{ $group->id }}"
@endisset>

    @isset($group)
        <header>
            <i class="grip" style="cursor: grab"></i>

            <form action="" method="post">
                <textarea class="auto-resize" autocomplete="off">{{ $group->name }}</textarea>
            </form>
        </header>

        <ul data-task-group-id="{{ $group->id }}">
            @each ('partials.project.board.task', $group->tasks, 'task')
        </ul>

        <form method="@yield('method', 'POST')"
            action="{{ route('project.task.new', ['project' => $project]) }}">
            @csrf
            <div class="input-group">
                <input aria-label="Create Task Name" aria-describedby="task-name"
                    class="form-control" id="name" type="text"
                    name="name" placeholder="Create Task" minlength=4 required>
                <button class="btn btn-primary" type="submit"><i
                        class="bi bi-plus"></i></button>
            </div>
            <input type="hidden" class="form-control" id="task_group"
                name="task_group_id" value="{{ $group->id }}">
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
            <input type="hidden" class="form-control" id="project"
                name="project_id" value="{{ $project->id }}">
        </form>
    @endisset
</div>
