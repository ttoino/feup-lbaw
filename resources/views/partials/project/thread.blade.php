@php($thread ??= new \App\Models\Thread())

<article id="thread" class="editable" data-thread-id="{{ $thread->id }}"
    data-render-attr="id,thread-id">
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

    @can('edit', $project)
        <div @class(['hstack', 'gap-2', 'd-none' => !$thread->editable])
            data-render-class-condition="editable,d-none,false">
            <button id="edit-thread-button" class="btn btn-outline-primary">
                <i class="bi bi-pencil"></i> Edit
            </button>
            <button id="delete-thread-button" class="btn btn-outline-danger">
                <i class="bi bi-trash"></i> Delete
            </button>
        </div>

        <form id="edit-thread-form" class="edit needs-validation" novalidate>
            <input type="hidden" name="id" value="{{ $thread->id }}"
                data-render-value="id">

            <div class="form-floating">
                <input aria-describedby="thread-title-feedback" class="form-control"
                    type="text" name="title" id="thread-title" placeholder=""
                    minlength=6 maxlength=50 required value="{{ $thread->title }}"
                    data-render-value="title">
                <label for="thread-title" class="form-label">Title</label>
                <div class="invalid-feedback" id="thread-title-feedback">
                    Invalid title
                </div>
            </div>

            <div class="form-floating">
                <textarea placeholder="" class="form-control auto-resize" id="thread-content"
                    aria-describedby="thread-content-feedback" name="content" minlength=6
                    maxlength=512 required data-render-value="content.raw">{{ $thread->content['raw'] }}</textarea>
                <a href="https://www.markdownguide.org/basic-syntax/"><i
                        class="bi bi-markdown"></i> Markdown is supported</a>
                <label for="thread-content" class="form-label">Content</label>
                <div class="invalid-feedback" id="thread-content-feedback">
                    Invalid content
                </div>
            </div>

            <button class="btn btn-primary" type="submit">Save changes</button>
        </form>
    @endcan
</article>

<ul id="thread-comments">
    @foreach ($thread->comments ?? [] as $threadComment)
        @include('partials.project.forum.comment', [
            'threadComment' => $threadComment,
        ])
    @endforeach
</ul>

<button @class([
    'btn',
    'btn-primary',
    'mx-auto',
    'mb-3',
    'd-none' =>
        $thread->comments instanceof \Illuminate\Pagination\CursorPaginator &&
        $thread->comments?->nextCursor() == null,
]) id="load-comments-button"
    data-render-class-condition="next_cursor,d-none,false"
    data-next-cursor="{{ $thread->comments instanceof \Illuminate\Pagination\CursorPaginator ? $thread->comments?->nextCursor()?->encode() : '' }}"
    data-render-attr="next_cursor,next-cursor">
    Load more comments
</button>

@can('edit', $project)
    <form id="new-comment-form" action="" method="post" class="input-group">
        <textarea name="content" id="thread-comment-content" placeholder="New comment"
            class="auto-resize form-control"></textarea>
        <input type="hidden" name="thread_id" value="{{ $thread->id }}"
            data-render-value="id">
        <button class="btn btn-primary" type="submit">
            <i class="bi bi-send"></i>
        </button>
    </form>
@endcan
