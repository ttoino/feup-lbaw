<li class="list-group-item list-group-item-action position-relative d-flex flex-row align-items-center gap-2"
    data-user-id="{{ $item->id }}">

    <img src="{{ asset($item->profile_pic) }}" alt="Profile picture" width="40"
        height="40" class="rounded-circle">

    <div class="vstack flex-fill align-self-center">
        <a href="{{ route('user.profile', ['user' => $item]) }}"
            @class([
                'stretched-link',
                'fw-bold',
                'underline' => Auth::user()?->id === $item->id,
            ])>
            {{ $item->name }}
        </a>

        @if ($item->is_admin)
            <span class="text-success">Admin</span>
        @endif

        @if ($item->blocked)
            <span class="text-danger">Blocked</span>
        @endif
    </div>

    @can('admin-action')
        <a href="{{ route('admin.reports.user', ['user' => $item]) }}"
            class="btn btn-outline-secondary" style="z-index: 5">Reports
            ({{ $item->reports->count() }})</a>
    @endcan

    @can('block', $item)
        <button class="btn btn-outline-secondary block-user"
            style="z-index: 5">Block</button>
    @endcan

    @can('unblock', $item)
        <button class="btn btn-outline-secondary unblock-user"
            style="z-index: 5">Unblock</button>
    @endcan

    @can('report', $item)
        <a href="{{ route('user.report', ['user' => $item]) }}"
            class="btn btn-outline-danger report-user" style="z-index: 5">
            Report
        </a>
    @endcan

    @can('delete', $item)
        <button class="btn btn-outline-danger delete-user" style="z-index: 5"><i
                class="bi bi-trash"></i></button>
    @endcan

    @isset($project)
        @can('removeUser', [$project, $item])
            <button class="btn btn-outline-danger remove-user" style="z-index: 5"><i
                    class="bi bi-x-lg"></i></button>
        @endcan
    @endisset
</li>
