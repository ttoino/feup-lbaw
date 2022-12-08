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
        @if ($item->reports_count > 0)
            <span class="text-danger" style="z-index: 5">
                <a href={{route('admin.reports.project', ['project' => $item])}} >{{ $item->reports_count }} Reports</a>
            </span>
        @endif    
        <button class="btn btn-outline-danger" style="z-index: 5"><i class="bi bi-trash3"></i></button>
    @else
        <button class="btn btn-outline favorite-toggle" style="z-index: 5">
            @php
                $isFavorite = $item->users()->get()->first(fn ($user) => $user->id === Auth::user()->id)->pivot->is_favorite;
            @endphp

            <i @class([
                'bi',
                'bi-heart' => !$isFavorite,
                'bi-heart-fill' => $isFavorite,
            ])></i>
        </button>
    @endif
    @if (Auth::user()?->id === $item->coordinator)
        <button class="btn btn-outline-danger" style="z-index: 5"><i class="bi bi-trash3"></i></button>
    @endif
</li>
