@php($thread ??= new \App\Models\Thread())

<li class="thread" data-thread-id="{{ $thread->id }}"
    data-render-attr="id,thread-id">
    <a href="{{ route('user.profile', ['user' => $thread->author]) }}"
        data-render-href="author_id,{{ str_replace('123456789', '{}', route('user.profile', ['user' => 123456789])) }}"
        role="button" style="z-index: 100">
        <img width="40" height="40" alt="Profile picture"
            data-render-src="author.profile_pic"
            src="{{ asset($thread->author?->profile_pic) }}"
            class="rounded-circle">
    </a>

    <div>
        <div>
            <a href="{{ route('project.thread', ['project' => $project, 'thread' => $thread->id ?? 0]) }}"
                data-render-href="id,{{ str_replace('123456789', '{}', route('project.thread', ['project' => $project, 'thread' => 123456789])) }}"
                data-render-text="title" @class(['fw-bold', 'stretched-link'])>
                {{ $thread->title }}
            </a>
            <time data-render-datetime="creation_date.iso"
                data-render-text="creation_date.diff"
                datetime="{{ $thread->creation_date['iso'] }}">
                {{ $thread->creation_date['diff'] }},
            </time>
        </div>
        <div>
            <a href="{{ route('user.profile', ['user' => $thread->author]) }}"
                data-render-href="author_id,{{ str_replace('123456789', '{}', route('user.profile', ['user' => 123456789])) }}"
                role="button" aria-expanded="false" style="z-index: 100">
                {{ $thread->author?->name }}
            </a>
            <span class="comments"
                data-render-attr="comments.length,comment-count"
                data-comment-count="{{ $thread->comments->count() }}">
                <i class="bi bi-reply"></i>
            </span>
        </div>
    </div>
</li>
