@once
    @push('navbar-left')
        <a class="navbar-toggler d-block d-lg-none me-3" type="button" data-bs-toggle="offcanvas" href="#drawer" role="button"
            aria-controls="drawer" aria-expanded="false" aria-label="Toggle drawer">
            <span class="navbar-toggler-icon"></span>
        </a>
    @endpush
@endonce

<aside class="offcanvas-lg offcanvas-start border-end p-2 flex-shrink-0" style="width: 270px" id="drawer">
    <nav class="nav nav-pills flex-column">
        <div class="dropdown nav-item">
            <a class="nav-link link-dark dropdown-toggle" data-bs-toggle="dropdown" href="#" role="button"
                aria-expanded="false">
                {{ $project->name }}
            </a>
            <ul class="dropdown-menu w-100 shadow-sm">
                @if ($other_projects->count())
                    @foreach ($other_projects as $p)
                        <li>
                            <a class="dropdown-item"
                                href="{{ route('project', ['id' => $p->id]) }}">{{ $p->name }}</a>
                        </li>
                    @endforeach

                    <hr class="dropdown-divider">

                    @if ($other_projects->hasMorePages())
                        <li>
                            <a href="{{ route('project.list') }}" class="dropdown-item">
                                All projects
                            </a>
                        </li>
                    @endif
                @endif

                <li>
                    <a class="dropdown-item" href="{{ route('project.new') }}">
                        <i class="bi bi-plus"></i> Create project
                    </a>
                </li>
            </ul>
        </div>

        <form method="GET" action="{{ route('project.task.search', ['id' => $project->id]) }}" class="input-group"
            role="search">
            <input class="form-control" name="q" type="search" placeholder="Search tasks" aria-label="Search"
                value="{{ Request::route()->getName() === 'project.task.search' ? Request::query('q', '') : '' }}">
            <button class="btn btn-outline-primary" type="submit"><i class="bi bi-search"></i></button>
        </form>

        @foreach ([['label' => 'Info', 'route' => 'project.info'], ['label' => 'Board', 'route' => 'project.board'], ['label' => 'Timeline', 'route' => 'project.timeline'], ['label' => 'Forum', 'route' => 'project.forum']] as $item)
            @include('partials.project.drawer.item', $item)
        @endforeach

        @if (Auth::user()->id == $project->coordinator)
            <a href="{{ route('project.user.add', ['id' => $project->id]) }}" @class([
                'nav-link',
                'nav-item',
                'active' => Request::route()->getName() === 'project.user.add',
            ])>
                <i @class(['bi', 'bi-plus'])></i> Add User
            </a>
        @endif

    </nav>
</aside>
