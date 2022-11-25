<li class="shadow-sm rounded p-2 bg-white position-relative hstack justify-content-between"
    data-task-id="{{ $item->id }}">
    <div class="vstack gap-1 align-self-center">
        @if (!$item->tags()->get()->isEmpty())
            <ul class="hstack list-unstyled gap-1">
                @foreach ($item->tags()->get() as $tag)
                    <li class="rounded-pill p-1"
                        style="background: rgb({{ $tag->rgbColor() }}); flex: 48px 0 1">
                    </li>
                @endforeach
            </ul>
        @endif

        <a class="stretched-link"
            href="{{ route('project.task.info', ['id' => $item->project, 'taskId' => $item->id]) }}">
            {{ $item->name }}
        </a>

        @if (!$item->assignees->isEmpty())
            <ul class="hstack list-unstyled gap-n1">
                @foreach ($item->assignees as $assignee)
                    <li>
                        <img src="https://picsum.photos/40"
                            alt="{{ $assignee->name }}" width="24"
                            height="24" class="rounded-circle">
                    </li>
                @endforeach
            </ul>
        @endif
    </div>
    <button class="btn btn-outline" style="z-index: 50">
        <i @class([
            'bi',
            'bi-check-circle' => $item->state !== 'completed',
            'bi-check-circle-fill' => $item->state === 'completed',
        ])></i>
    </button>
</li>