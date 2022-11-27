<li class="list-group-item list-group-item-action position-relative d-flex flex-row align-items-center gap-2"
    data-project-id="{{ $item->id }}">
    <div class="vstack flex-fill">
        <a href="{{ route('project', ['project' => $item]) }}" class="stretched-link fw-bold">
            {{ $item->name }}
        </a>
        <span>Coordinator:
            {{ $item->coordinator()->first()->name }}</span>
    </div>

    @if (Auth::user()?->is_admin)
        <span class="text-danger">
            {{ $item->reports_count }} Reports
        </span>
        <button class="btn btn-outline-danger" style="z-index: 5"><i class="bi bi-trash3"></i></button>
    @else
        <button class="btn btn-outline" style="z-index: 5"><i class="bi bi-heart"></i></button>
    @endif
    @if (Auth::user()?->id === $item->coordinator)
        <button class="btn btn-outline-danger" style="z-index: 5"><i class="bi bi-trash3"></i></button>
    @endif
</li>
