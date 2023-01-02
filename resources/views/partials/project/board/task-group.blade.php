@php($project ??= $group->project)

<div class="task-group"
    @isset($group) data-task-group-id="{{ $group->id }}" data-render-attr="id,task-group-id" @endisset>

    @isset($group)
        <header>
            @if (!$project->archived)
                <i class="grip" style="cursor: grab"></i>
            @endif

            <form class="edit-task-group-form">
                <textarea class="auto-resize single-line" autocomplete="off"
                    data-render-value="name" name="name" minlength="4" required
                    @if ($project->archived) disabled @endif>{{ $group->name }}</textarea>
            </form>

            @if (!$project->archived)
                <button type="button" @class(['delete-task-group', 'd-none' => $group->tasks->count()])><i
                        class="bi bi-trash"></i></button>
            @endif
        </header>

        <ul>@each('partials.project.board.task', $group->tasks, 'task')</ul>

        @if (!$project->archived)
            <form class="input-group new-task-form">
                <textarea class="auto-resize single-line form-control" autocomplete="off"
                    placeholder="Create Task" name="name" minlength="4" required></textarea>
                <button class="btn btn-primary" type="submit"><i
                        class="bi bi-plus"></i></button>
            </form>
        @endif
    @else
        <form class="input-group needs-validation" id="new-task-group-form"
            novalidate>
            <textarea class="auto-resize single-line form-control" autocomplete="off"
                placeholder="Create Group" name="name" minlength="4" required></textarea>
            <button class="btn btn-primary" type="submit"><i
                    class="bi bi-plus"></i></button>
        </form>
    @endisset
</div>
