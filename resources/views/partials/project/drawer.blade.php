@once
    @push('navbar-left')
        <a class="navbar-toggler d-block d-lg-none me-3" type="button"
            data-bs-toggle="offcanvas" href="#drawer" role="button" aria-controls="drawer"
            aria-expanded="false" aria-label="Toggle drawer">
            <span class="navbar-toggler-icon"></span>
        </a>
    @endpush
@endonce

<aside class="offcanvas-lg offcanvas-start border-end p-2 flex-shrink-0"
    style="width: 270px" id="drawer">
    <nav class="nav nav-pills flex-column">
        <div class="dropdown nav-item">
            <a class="nav-link link-dark dropdown-toggle"
                data-bs-toggle="dropdown" href="#" role="button"
                aria-expanded="false">
                {{ $project->name }}
            </a>
            <ul class="dropdown-menu w-100 shadow-sm">
                @foreach ($other_projects as $p)
                    <li>
                        <a class="dropdown-item"
                            href="{{ route('project.home', ['id' => $p->id]) }}">{{ $p->name }}</a>
                    </li>
                @endforeach

                <hr class="dropdown-divider">
                <li>
                    <a class="dropdown-item" href="{{ route('project.new') }}">
                        <i class="bi bi-plus"></i> Create project
                    </a>
                </li>
            </ul>
        </div>
        @foreach ([
            // TODO: change this to routes once they are in place
            ['label' => 'Info', 'path' => "project/$project->id/info"],
            ['label' => 'Board', 'path' => "project/$project->id"],
            ['label' => 'Timeline', 'path' => "project/$project->id/timeline"],
            ['label' => 'Forum', 'path' => "project/$project->id/forum"]
        ] as $item)
            @include('partials.project.drawer.item', $item)
        @endforeach
    </nav>
</aside>
