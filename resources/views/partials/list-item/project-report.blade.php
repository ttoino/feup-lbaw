<li class="list-group-item list-group-item-action position-relative d-flex flex-row align-items-center gap-2"
    data-project-id="{{ $item->id }}">
    <div class="vstack flex-fill">
        <a href="{{ route('project', ['project' => $item->project]) }}" class="stretched-link fw-bold">
            Report on {{ $item->project()->first()->name }}
        </a>
        <span>Reason:
            {{ $item->reason }}</span>
    </div>
</li>
