@php($threadComment ??= new \App\Models\ThreadComment())

<li class="thread-comment editable" data-thread-comment-id="{{ $threadComment->id }}"
    data-render-attr="id,thread-comment-id">
    @include('partials.project.forum.thread-comment-common', [
        'item' => $threadComment,
    ])

    @can('edit', $project)
        <div @class(['hstack', 'gap-2', 'd-none' => !$threadComment->editable]) data-render-class-condition="editable,d-none,false">
            <button class="edit-thread-comment-button btn btn-outline-primary">
                <i class="bi bi-pencil"></i> Edit
            </button>
            <button class="delete-thread-comment-button btn btn-outline-danger">
                <i class="bi bi-trash"></i> Delete
            </button>
        </div>

        <form class="edit-thread-comment-form edit needs-validation" novalidate>
            <input type="hidden" name="id" value="{{ $threadComment->id }}" data-render-value="id">

            <div class="form-floating">
                <textarea placeholder="" class="form-control auto-resize" id="thread-comment-content"
                    aria-describedby="thread-comment-content-feedback" name="content" minlength=6 maxlength=512 required
                    data-render-value="content.raw">{{ $threadComment->content['raw'] }}</textarea>
                <a href="https://www.markdownguide.org/basic-syntax/"><i class="bi bi-markdown"></i> Markdown is
                    supported</a>
                <label for="thread-comment-content" class="form-label">Content</label>
                <div class="invalid-feedback" id="thread-comment-content-feedback">
                    Invalid content
                </div>
            </div>

            <button class="btn btn-primary" type="submit">Save changes</button>
        </form>
    @endcan
</li>
