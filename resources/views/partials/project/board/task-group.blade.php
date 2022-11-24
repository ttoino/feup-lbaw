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
]) style="width: 270px">

    @isset($group)
        <p class="m-0 text-center">{{ $group->name }}</p>

        @if (!$group->tasks->isEmpty())
            <ul class="list-unstyled vstack gap-2 p-2"
                style="overflow-y: auto; margin: -.5rem">
                @each ('partials.project.board.task', $group->tasks, 'task')
            </ul>
        @endif

        <form method="POST" action="{{ url("/project/$group->project/task/new") }}">
            @csrf
            <div class="input-group">
                <input aria-label="Create Task Name" aria-describedby="task-name"
                    class="form-control" id="name" type="text" name="name"
                    placeholder="Create Task" required>
                <button class="btn btn-primary" type="submit"><i
                        class="bi bi-plus"></i></button>
            </div>
            <input type="hidden" class="form-control" id="task_group"
                name="task_group" value="{{ $group->id }}">
        </form>
    @else
        <form method="POST"
            action="{{ url("/project/$project->id/task-group/new") }}">
            @csrf
            <div class="input-group">
                <input aria-label="Create Group Name" aria-describedby="group-name"
                    class="form-control" id="name" type="text"
                    name="name" placeholder="Create Group" required>
                <button class="btn btn-primary" type="submit"><i
                        class="bi bi-plus"></i></button>
            </div>
            <input type="hidden" class="form-control" id="position"
                name="position" value="{{ count($project->taskGroups) + 1 }}">
            <input type="hidden" class="form-control" id="project" name="project"
                value="{{ $project->id }}">
        </form>
    @endisset
</div>
