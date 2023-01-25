<li class="list-group-item list-group-item-action position-relative d-flex flex-row align-items-center gap-2"
    data-project-id="{{ $item->id }}">
    <div class="vstack flex-fill">
        <a href="{{ route('project', ['project' => $item]) }}" class="stretched-link fw-bold">
            {{ $item->name }}
        </a>
        <span>Coordinator:
            {{ $item->coordinator->name }}</span>
    </div>

    @if ($item->archived)
        <span class="text-warning">Archived</span>
    @endif

    @can('admin-action')
        <a href="{{ route('admin.reports.project', ['project' => $item]) }}" class="btn btn-outline-secondary"
            style="z-index: 5">Reports
            ({{ $item->reports->count() }})</a>
    @endcan

    @can('toggleFavorite', $item)
        <button class="btn btn-outline-primary favorite-toggle" style="z-index: 5">
            <i @class([
                'bi',
                'bi-heart' => !$item->pivot->is_favorite,
                'bi-heart-fill' => $item->pivot->is_favorite,
            ])></i>
        </button>
    @endcan

    @can('delete', $item)
        <button class="btn btn-outline-danger delete-project" style="z-index: 5">
            <i class="bi bi-trash"></i>
        </button>
    @endcan
</li>
