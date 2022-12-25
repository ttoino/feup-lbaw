<li class="thread-comment" data-thread-comment-id="{{ $threadComment->id }}">
    @include('partials.project.forum.thread-comment-common', [
        'item' => $threadComment,
    ])
</li>
