@php($tag ??= new \App\Models\Tag())

<li style="--tag-color: {{ $tag->color }}"
    data-render-css-var="color,tag-color">
    <a href="{{ route('project.task.search', ['project' => $tag->project ?? $project, 'q' => $tag->title]) }}"
        data-render-href="title,{{ route('project.task.search', ['project' => $tag->project ?? $project]) }}?q={}"
        data-render-text="title">
        {{ $tag->title }}
    </a>
</li>
