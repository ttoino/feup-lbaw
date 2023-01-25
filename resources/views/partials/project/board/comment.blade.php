@php($taskComment ??= new \App\Models\TaskComment())

<li class="task-comment editable" data-task-comment-id="{{ $taskComment->id }}" data-render-attr="id,task-comment-id">
    @include('partials.project.forum.thread-comment-common', [
        'item' => $taskComment,
    ])

    @can('edit', $project)
        <div @class(['hstack', 'gap-2', 'd-none' => !$taskComment->editable]) data-render-class-condition="editable,d-none,false">
            <button class="edit-task-comment-button btn btn-outline-primary">
                <i class="bi bi-pencil"></i> Edit
            </button>
            <button class="delete-task-comment-button btn btn-outline-danger">
                <i class="bi bi-trash"></i> Delete
            </button>
        </div>

        <form class="edit-task-comment-form edit needs-validation" novalidate>
            <input type="hidden" name="id" value="{{ $taskComment->id }}" data-render-value="id">

            <div class="form-floating">
                <textarea placeholder="" class="form-control auto-resize" id="task-comment-content"
                    aria-describedby="task-comment-content-feedback" name="content" minlength=6 maxlength=512 required
                    data-render-value="content.raw">{{ $taskComment->content['raw'] }}</textarea>
                <a href="https://www.markdownguide.org/basic-syntax/"><i class="bi bi-markdown"></i> Markdown is
                    supported</a>
                <label for="task-comment-content" class="form-label">Content</label>
                <div class="invalid-feedback" id="task-comment-content-feedback">
                    Invalid content
                </div>
            </div>

            <button class="btn btn-primary" type="submit">Save changes</button>
        </form>
    @endcan
</li>
