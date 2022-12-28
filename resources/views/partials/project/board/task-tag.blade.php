@php($tag ??= new \App\Models\Tag())

<li style="--tag-color: {{ $tag->color }}"
    data-render-css-var="color,tag-color">
</li>
