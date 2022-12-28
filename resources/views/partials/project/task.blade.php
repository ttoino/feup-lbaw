@php($task ??= new \App\Models\Task())

<header>
    <h2 class="offcanvas-title h4" data-render-text="name">{{ $task->name }}
    </h2>
    <button type="button" class="btn-close" data-bs-dismiss="offcanvas"
        aria-label="Close"></button>
</header>

<x-markdown id="task-description" data-render-html="description_formatted">
    {!! $task->description !!}</x-markdown>

<form method="GET"
    action="{{ route('project.task.search', ['project' => $project]) }}"
    class="chip-search-form">
    <input type="hidden" name="q">
    <ul class="list-unstyled hstack gap-2" data-render-attr="tags.length,length"
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

<h3 class="h5">Comments</h3>

@if (!$task->comments->isEmpty())
    @include('partials.list', [
        'paginator' => $task?->comments,
        'itemView' => 'partials.list-item.task-comment',
    ])
@endif

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
