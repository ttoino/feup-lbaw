@props([
    'color' => 'primary',
    'type' => 'button',
    'outline' => false,
    'icon' => false,
])
@php($tag = $type == 'link' ? 'a' : 'button')

<{{ $tag }} {{ $attributes->class([
    'btn',
    'btn-' . ($outline ? 'outline-' : '') . $color
    ]) }} @if ($type != 'link') type={{ $type }} @endif>
    @if ($icon)
        <i class="bi bi-{{ $icon }}"></i>
    @endif
    {{ $slot }}
</{{ $tag }}>
