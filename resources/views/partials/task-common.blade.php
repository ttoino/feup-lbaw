<div class="vstack gap-1 align-self-center">
    <ul class="tags" data-render-list="tags,#tag-template">
        @each('partials.project.board.task-tag', $task->tags, 'tag')
    </ul>

    <a class="stretched-link" data-render-text="name"
        href="{{ route('project.task.info', ['project' => $project, 'task' => $task->id ?? 0]) }}">
        {{ $task->name }}
    </a>

    <div class="bottom-row">
        <ul class="assignees" data-render-list="assignees,#assignee-template" data-render-attr="assignees.length,length"
            data-length="{{ $task->assignees->count() }}">
            @each('partials.project.board.task-assignee', $task->assignees, 'assignee')</ul>
        <span class="comments" data-render-attr="comments.length,comment-count"
            data-comment-count="{{ $task->comments->count() }}">
            <i class="bi bi-reply"></i>
        </span>
    </div>
</div>

<i data-render-class-condition="completed,d-none,false" @class(['completed-check', 'd-none' => !$task->completed])></i>
