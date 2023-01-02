@php
    $author ??= 'author';
    $content ??= 'content';
    $creation_date ??= 'creation_date';
@endphp

<a href="{{ route('user.profile', ['user' => $item->$author ?? 0]) }}"
    data-render-href="{{ $author }}_id,{{ str_replace('123456789', '{}', route('user.profile', ['user' => 123456789])) }}"
    role="button" style="z-index: 100" class="hstack gap-2">
    <img width="40" height="40" alt="Profile picture"
        src="{{ asset($item->$author?->profile_pic) }}" class="rounded-circle"
        data-render-src="{{ $author }}.profile_pic">
    <div class="vstack">
        <span
            data-render-text="{{ $author }}.name">{{ $item->$author?->name }}</span>
        <time data-render-datetime="{{ $creation_date }}.iso"
            data-render-text="{{ $creation_date }}.datetime"
            datetime="{{ $item->$creation_date['iso'] }}">
            {{ $item->$creation_date['datetime'] }}
        </time>
    </div>
</a>

<div class="content" data-render-html="{{ $content }}.formatted">
    {!! $item->$content['formatted'] !!}
</div>
