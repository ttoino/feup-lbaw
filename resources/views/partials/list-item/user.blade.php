<li class="list-group-item list-group-item-action position-relative d-flex flex-row align-items-center gap-2"
    data-project-id="{{ $item->id }}">
    <img src="https://picsum.photos/40" alt="Profile picture" width="40"
        height="40" class="rounded-circle">

    <a href="{{ route('user.profile', ['id' => $item->id]) }}"
        class="stretched-link fw-bold flex-fill">
        {{ $item->name }}
    </a>

    @yield('right-side')
</li>
