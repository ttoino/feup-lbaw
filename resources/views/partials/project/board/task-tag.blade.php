@php($tag ??= new \App\Models\Tag())

<li style="--tag-color: {{ $tag->rgb_color }}" data-render-css-var="rgb_color,tag-color">
    <a href="{{ route('project.tasks', ['project' => $tag->project ?? $project, 'q' => $tag->title]) }}"
        data-render-href="title,{{ route('project.tasks', ['project' => $tag->project ?? $project]) }}?q={}"
        data-render-text="title">
        {{ $tag->title }}
    </a>
</li>
