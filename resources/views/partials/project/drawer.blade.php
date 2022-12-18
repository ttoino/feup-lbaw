@once
    @push('navbar-left')
        <button class="btn btn-outline-light d-block d-lg-none" type="button"
            data-bs-toggle="offcanvas" data-bs-target="#project-drawer"
            aria-controls="drawer" aria-expanded="false"
            aria-label="Toggle project drawer">
            <i class="bi bi-list"></i>
        </button>
    @endpush
@endonce

<aside id="project-drawer">
    <nav>
        <div class="dropdown">
            <a data-bs-toggle="dropdown" href="#" role="button"
                aria-expanded="false">
                {{ $project->name }}
            </a>
            <ul class="dropdown-menu">
                @if ($other_projects->count())
                    @foreach ($other_projects as $p)
                        <li>
                            <a href="{{ route('project', ['project' => $p]) }}">
                                {{ $p->name }}
                            </a>
                        </li>
                    @endforeach

                    <hr class="dropdown-divider">

                    @if ($other_projects->hasMorePages())
                        <li>
                            <a href="{{ route('project.list') }}">
                                All projects
                            </a>
                        </li>
                    @endif
                @endif

                <li>
                    <a href="{{ route('project.new') }}">
                        <i class="bi bi-plus"></i> Create project
                    </a>
                </li>
            </ul>
        </div>

        <form method="GET"
            action="{{ route('project.task.search', ['project' => $project]) }}"
            class="input-group my-2" role="search">
            <input class="form-control" name="q" type="search"
                placeholder="Search tasks" aria-label="Search"
                value="{{ Request::route()->getName() === 'project.task.search' ? Request::query('q', '') : '' }}">
            <button class="btn btn-outline-primary" type="submit"><i
                    class="bi bi-search"></i></button>
        </form>

        @foreach ([['label' => 'Info', 'route' => 'project.info'], ['label' => 'Board', 'route' => 'project.board'], ['label' => 'Timeline', 'route' => 'project.timeline'], ['label' => 'Forum', 'route' => 'project.forum']] as $item)
            @include('partials.project.drawer.item', $item)
        @endforeach

        @if (Auth::user()->id == $project->coordinator_id)
            <a href="{{ route('project.user.add', ['project' => $project]) }}"
                @class([
                    'nav-link',
                    'nav-item',
                    'active' => Request::route()->getName() === 'project.user.add',
                ])>
                <i @class(['bi', 'bi-plus'])></i> Add User
            </a>
        @endif

    </nav>
</aside>
