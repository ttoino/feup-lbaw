<nav class="nav nav-pills">
    @foreach ([
        ['label' => 'Users', 'route' => 'admin.users'],
        ['label' => 'Projects', 'route' => 'admin.projects']
    ] as $item)
        @include('partials.admin.nav.item', $item)
    @endforeach
</nav>
