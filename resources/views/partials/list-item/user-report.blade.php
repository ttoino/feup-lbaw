<li class="list-group-item list-group-item-action position-relative d-flex flex-row align-items-center gap-2"
    data-project-id="{{ $item->id }}">
    <div class="vstack flex-fill">
        <a href="{{ route('user.profile', ['user' => $item->user]) }}" class="stretched-link fw-bold">
            Report on {{ $item->user()->first()->name }}
        </a>
        <span>Reason:
            {{ $item->reason }}</span>
    </div>
    @if (Auth::user()?->is_admin)
        <button class="btn btn-outline-danger" style="z-index: 5"><i class="bi bi-trash3"></i></button>
    @endif
</li>