<div @class([
    'shadow-sm',
    'rounded',
    'p-2',
    'text-center',
    'd-flex',
    'flex-column',
    'gap-2',
    'mh-100',
    'bg-light',
    'flex-shrink-0',
]) style="width: 270px; overflow-y: auto">

    @isset($group)
        <p class="m-0">{{ $group->name }}</p>
        @foreach ($group->tasks as $t)
            <a href="{{ route('project.task.info', ['id' => $group->project, 'taskId' => $t->id]) }}"
                class="shadow-sm rounded p-1 bg-white">{{ $t->name }}</a>
        @endforeach
        <form method="POST" action="{{ url("/project/$group->project/task/new") }}">
            @csrf
            <div class="input-group">
                <input aria-label="Create Task Name" aria-describedby="task-name"
                    class="form-control" id="name" type="text" name="name"
                    placeholder="Create Task" required>
                <button class="btn btn-primary" type="submit"><i
                        class="bi bi-plus"></i></button>
            </div>
            <input type="hidden" class="form-control" id="position"
                name="position" value="{{ count($group->tasks) + 1 }}">
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
