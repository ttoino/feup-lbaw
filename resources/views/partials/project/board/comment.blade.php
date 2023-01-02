@php($taskComment ??= new \App\Models\TaskComment())

<li class="task-comment" data-task-comment-id="{{ $taskComment->id }}"
    data-render-attr="id,task-comment-id">
    @include('partials.project.forum.thread-comment-common', [
        'item' => $taskComment,
    ])
</li>
