<a href="{{ route('user.profile', ['user' => $item->author ?? 0]) }}"
    data-render-href="author_id,{{ str_replace('123456789', '{}', route('user.profile', ['user' => 123456789])) }}"
    role="button" style="z-index: 100" class="hstack gap-2">
    <img width="40" height="40" alt="Profile picture"
        src="{{ asset($item->author?->profile_pic) }}" class="rounded-circle"
        data-render-src="author.profile_pic">
    <div class="vstack">
        <span data-render-text="author.name">{{ $item->author?->name }}</span>
        <time data-render-datetime="creation_date"
            data-render-text="creation_date"
            datetime="{{ $item->creation_date }}">
            @date($item->creation_date)
        </time>
    </div>
</a>

<x-markdown class="content" data-render-html="content_formatted">
    {!! $item->content !!}
</x-markdown>
