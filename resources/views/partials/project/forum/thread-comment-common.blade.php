<a href="{{ route('user.profile', ['user' => $item->author ?? 0]) }}"
    role="button" style="z-index: 100" class="hstack gap-2">
    <img width="40" height="40" alt="Profile picture"
        src="{{ asset($item->author?->getProfilePicture()) }}"
        class="rounded-circle" data-render-prop="author_id"
        data-render-method="profile_pic">
    <div class="vstack">
        <span data-render-prop="author_id">{{ $item->author?->name }}</span>
        <time data-render-prop="creation_date" data-render-method="date"
            datetime="{{ $item->creation_date }}">
            @date($item->creation_date)
        </time>
    </div>
</a>

<x-markdown class="content" data-render-prop="content"
    data-render-method="html">{!! $item->content !!}</x-markdown>
