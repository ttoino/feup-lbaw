@php($task ??= new \App\Models\Task())

<article id="task">
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

    <form method="GET"
        action="{{ route('project.task.search', ['project' => $project]) }}"
        class="chip-search-form">
        <input type="hidden" name="q">
        <ul class="list-unstyled hstack gap-2"
            data-render-attr="tags.length,length"
            data-length="{{ $task->tags->count() }}">
            @foreach ($task->tags as $tag)
                <li class="p-1 rounded border"
                    style="background: rgba({{ $tag->color }}, 30%); cursor: pointer; --bs-border-color: rgb({{ $tag->color }}); color: rgb({{ $tag->color }})">
                    {{ $tag->title }}
                </li>
            @endforeach
        </ul>
    </form>

    <h3 class="h5">Assignees</h3>

    @include('partials.list', [
        'paginator' => $task->assignees,
        'itemView' => 'partials.list-item.user',
    ])
</article>

<ul id="task-comments" data-render-list="comments,#task-comment-template">
    @each('partials.project.board.comment', $task->comments, 'taskComment')
</ul>

<form method="POST" class="input-group"
    action="{{ route('project.task.comment', ['project' => $project, 'task' => $task->id ?? 'task']) }}">
    <textarea class="form-control auto-resize" name="content" required
        placeholder="New comment" @if ($project->archived) disabled @endif></textarea>

    @csrf

    <button class="btn btn-primary" type="submit"
        @if ($project->archived) disabled @endif>
        <i class="bi bi-send"></i>
    </button>
</form>
