@php($thread ??= new \App\Models\Thread())

<article id="thread">
    <header class="offcanvas-header">
        <h2 class="offcanvas-title" data-render-text="title">
            {{ $thread->title }}
        </h2>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas"
            data-bs-target="#thread-offcanvas" aria-label="Close"></button>
    </header>

    @include('partials.project.forum.thread-comment-common', [
        'item' => $thread,
    ])
</article>

<ul id="thread-comments">
    @each('partials.project.forum.comment', $thread->comments, 'threadComment')
</ul>

<form id="new-comment-form" action="" method="post" class="input-group">
    <textarea name="content" id="thread-comment-content" placeholder="New comment"
        class="auto-resize form-control"
        @if ($project->archived) disabled @endif></textarea>
    <input type="hidden" name="thread_id" value="{{ $thread->id }}"
        data-render-value="id">
    <button class="btn btn-primary" type="submit"
        @if ($project->archived) disabled @endif>
        <i class="bi bi-send"></i>
    </button>
</form>
