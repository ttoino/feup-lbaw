<li class="list-group-item list-group-item-action position-relative d-flex flex-row align-items-center"
    data-project-id="{{ $item->id }}">
    <div class="vstack flex-fill">
        <a href="{{ route('project', ['id' => $item->id]) }}"
            class="stretched-link fw-bold">
            {{ $item->name }}
        </a>
        <span>Coordinator:
            {{ $item->coordinator()->first()->name }}</span>
    </div>

    @if (Auth::user()?->is_admin)
        <span class="text-danger">
            {{ $item->reports_count }} Reports
        </span>
    @else
        <button class="btn btn-outline" style="z-index: 5"><i
                class="bi bi-heart"></i></button>
    @endif
</li>
