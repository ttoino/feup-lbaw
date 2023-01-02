@php($threadComment ??= new \App\Models\ThreadComment())

<li class="thread-comment" data-thread-comment-id="{{ $threadComment->id }}"
    data-render-attr="id,thread-comment-id">
    @include('partials.project.forum.thread-comment-common', [
        'item' => $threadComment,
    ])
</li>
