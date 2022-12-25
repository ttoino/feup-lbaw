<a href="{{ route('user.profile', ['user' => $item->author ?? 0]) }}"
    role="button" style="z-index: 100" class="hstack gap-2">
    <img width="40" height="40" alt="Profile picture"
        src="{{ asset($item->author?->getProfilePicture()) }}"
        class="rounded-circle author-avatar">
    <div class="vstack">
        <span class="author">{{ $item->author?->name }}</span>
        <time class="date" datetime="{{ $item->creation_date }}">
            @date($item->creation_date)
        </time>
    </div>
</a>

<x-markdown class="content">{!! $item->content !!}</x-markdown>
