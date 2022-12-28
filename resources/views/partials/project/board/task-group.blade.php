@php($project ??= $group->project)

<div class="task-group"
    @isset($group) data-task-group-id="{{ $group->id }}" data-render-attr="id,task-group-id" @endisset>

    @isset($group)
        <header>
            @if (!$project->archived)
                <i class="grip" style="cursor: grab"></i>
            @endif

            <form action="" method="post">
                <textarea class="auto-resize disabled" autocomplete="off" data-render-value="name"
                    @if ($project->archived) disabled @endif>{{ $group->name }}</textarea>
            </form>
        </header>

        <ul data-task-group-id="{{ $group->id }}">@each('partials.project.board.task', $group->tasks, 'task')</ul>

        @if (!$project->archived)
            <form class="input-group new-task-form">
                <input aria-label="Create Task Name" aria-describedby="task-name"
                    class="form-control" id="name" type="text"
                    name="name" placeholder="Create Task" minlength=4 required>
                <button class="btn btn-primary" type="submit"><i
                        class="bi bi-plus"></i></button>
            </form>
        @endif
    @else
        <form class="input-group" id="new-task-group-form">
            <input aria-label="Create Group Name" aria-describedby="group-name"
                class="form-control" id="name" type="text" name="name"
                placeholder="Create Group" minlength=4 required>
            <button class="btn btn-primary" type="submit"><i
                    class="bi bi-plus"></i></button>
        </form>
    @endisset
</div>
