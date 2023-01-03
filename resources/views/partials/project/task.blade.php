@php($task ??= new \App\Models\Task())

<article id="task" data-task-id="{{ $task->id }}"
    data-render-attr="id,task-id">
    <header class="offcanvas-header">
        <h2 class="offcanvas-title" data-render-text="name">{{ $task->name }}
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
