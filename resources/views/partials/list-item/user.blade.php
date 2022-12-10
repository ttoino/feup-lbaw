<li class="list-group-item list-group-item-action position-relative d-flex flex-row align-items-center gap-2"
    data-user-id="{{ $item->id }}">
    <img src="{{ asset($item->getProfilePicture()) }}" alt="Profile picture" width="40" height="40" class="rounded-circle">

    <div class="vstack flex-fill align-self-center">
        <a href="{{ route('user.profile', ['user' => $item]) }}" @class([
            'stretched-link',
            'fw-bold',
            'underline' => Auth::user()?->id === $item->id,
        ])>
            {{ $item->name }}
        </a>

        @if ($item->is_admin)
            <span class="text-success">Admin</span>
        @endif
    </div>

    @if (Auth::user()?->is_admin)
        @if ($item->reports_count > 0)
            <span class="text-danger" style="z-index: 5">
                <a href={{route('admin.reports.user', ['user' => $item])}} >{{ $item->reports_count }} Reports</a>
            </span>
        @endif    
        <button class="btn btn-outline-danger" style="z-index: 5"><i class="bi bi-trash3"></i></button>
    @endif
</li>
