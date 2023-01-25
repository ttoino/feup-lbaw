@php
    $author ??= 'author';
    $content ??= 'content';
    $creation_date ??= 'creation_date';
    $edit_date ??= 'edit_date';
@endphp

<a href="{{ route('user.profile', ['user' => $item->$author ?? 0]) }}"
    data-render-href="{{ $author }}_id,{{ str_replace('123456789', '{}', route('user.profile', ['user' => 123456789])) }}"
    role="button" style="z-index: 100" class="hstack gap-2">
    <img width="40" height="40" alt="Profile picture" src="{{ asset($item->$author?->profile_pic) }}"
        class="rounded-circle" data-render-src="{{ $author }}.profile_pic">
    <div class="vstack">
        <span data-render-text="{{ $author }}.name">{{ $item->$author?->name }}</span>
        <time data-render-datetime="{{ $creation_date }}.iso" data-render-text="{{ $creation_date }}.datetime"
            datetime="{{ $item->$creation_date ? $item->$creation_date['iso'] : null }}">
            {{ $item->$creation_date ? $item->$creation_date['datetime'] : null }}
        </time>
    </div>
</a>

<div class="content" data-render-html="{{ $content }}.formatted">
    {!! $item->$content['formatted'] !!}

</div>

<span @class(['d-none' => $item->$edit_date == null, 'fst-italic']) data-render-class-condition="edit_date,d-none,false">Edited <time
        data-render-datetime="{{ $edit_date }}.iso" data-render-text="{{ $edit_date }}.long_diff"
        datetime="{{ $item->$edit_date ? $item->$edit_date['iso'] : null }}">
        {{ $item->$edit_date ? $item->$edit_date['long_diff'] : null }}</time>
</span>
