<li class="list-group-item list-group-item-action position-relative d-flex flex-row align-items-center gap-2"
    data-project-id="{{ $item->id }}">
    <img src="https://picsum.photos/40" alt="Profile picture" width="40"
        height="40" class="rounded-circle">

    <div class="vstack flex-fill align-self-center">
        <a href="{{ route('user.profile', ['id' => $item->id]) }}"
            class="stretched-link fw-bold">
            {{ $item->name }}
        </a>

        @if ($item->is_admin)
            <span class="text-success">Admin</span>
        @endif
    </div>

    @if (Auth::user()?->is_admin)
        <span class="text-danger">
            {{ $item->reports_count }} Reports
        </span>
    @endif
</li>
