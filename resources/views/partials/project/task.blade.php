@php($task ??= new \App\Models\Task())

<article id="task" data-task-id="{{ $task->id }}" class="editable"
    data-render-attr="id,task-id">
    <header class="offcanvas-header">
        <h2 class="offcanvas-title" data-render-text="name">{{ $task->name }} <i
                @class(['bi', 'bi-check-lg', 'd-none' => !$task->completed])
                data-render-class-condition="completed,d-none,false"></i>
        </h2>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas"
            aria-label="Close"></button>
    </header>

    @include('partials.project.forum.thread-comment-common', [
        'item' => $task,
        'author' => 'creator',
        'content' => 'description',
    ])

    <ul class="tags" data-render-attr="tags.length,length"
        data-render-list="tags,#tag-template"
        data-length="{{ $task->tags->count() }}">
        @each('partials.project.board.task-tag', $task->tags, 'tag')
    </ul>

    <ul class="assignees" data-render-attr="assignees.length,length"
        data-render-list="assignees,#assignee-template"
        data-length="{{ $task->assignees->count() }}">
        @each('partials.project.board.task-assignee', $task->assignees, 'assignee')
    </ul>

    @can('edit', $project)
        <div class="hstack gap-2">
            <button data-render-class-condition="completed,d-none"
                id="complete-task-button" @class(['btn', 'btn-outline-secondary', 'd-none' => $task->completed])>
                <i class="bi bi-check-lg"></i> Mark as completed
            </button>
            <button data-render-class-condition="completed,d-none,false"
                id="incomplete-task-button" @class([
                    'btn',
                    'btn-outline-secondary',
                    'd-none' => !$task->completed,
                ])>
                <i class="bi bi-x-lg"></i> Mark as incomplete
            </button>
            <button id="edit-task-button" class="btn btn-outline-primary">
                <i class="bi bi-pencil"></i> Edit
            </button>
            <button id="delete-task-button" class="btn btn-outline-danger">
                <i class="bi bi-trash"></i> Delete
            </button>
        </div>

        <form class="edit needs-validation" id="edit-task-form" novalide>
            <input type="hidden" name="id" value="{{ $task->id }}"
                data-render-value="id">

            <div class="form-floating">
                <input aria-describedby="task-name-feedback" placeholder=""
                    class="form-control" type="text" name="name"
                    id="task-name" data-render-value="name"
                    value="{{ $task->name }}" minlength=4 maxlength=255 required>
                <label for="task-name" class="form-label">Name</label>
                <div id="task-name-feedback" class="invalid-feedback">
                    Please enter a valid name.
                </div>
            </div>

            <div class="form-floating">
                <textarea placeholder="" class="form-control auto-resize"
                    aria-describedby="task-description-feedback" name="description"
                    id="task-description" data-render-value="description.raw" minlength=6
                    maxlength=512>{{ $task->description['raw'] }}</textarea>
                <label for="task-description" class="form-label">Description</label>
                <div id="task-description-feedback" class="invalid-feedback">
                    Please enter a valid description.
                </div>
            </div>

            <button type="submit" class="btn btn-primary">Save changes</button>
        </form>
    @endcan
</article>

<ul id="task-comments" data-render-list="comments,#task-comment-template">
    @each('partials.project.board.comment', $task->comments, 'taskComment')
</ul>

@can('edit', $project)
    <form id="new-comment-form" class="input-group">
        <textarea class="form-control auto-resize" name="content" required
            placeholder="New comment"></textarea>
        <input type="hidden" name="task_id" value="{{ $task->id }}"
            data-render-value="id">
        <button class="btn btn-primary" type="submit">
            <i class="bi bi-send"></i>
        </button>
    </form>
@endcan
