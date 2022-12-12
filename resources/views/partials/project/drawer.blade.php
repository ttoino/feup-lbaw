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
            <a class="nav-link link-dark dropdown-toggle text-wrap" data-bs-toggle="dropdown" href="#" role="button"
                aria-expanded="false">
                {{ $project->name }}
            </a>
            <ul class="dropdown-menu w-100 shadow-sm">
                @if ($other_projects->count())
                    @foreach ($other_projects as $p)
                        <li>
                            <a class="dropdown-item"
                                href="{{ route('project', ['project' => $p]) }}">{{ $p->name }}</a>
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

        <form method="GET" action="{{ route('project.task.search', ['project' => $project]) }}"
            class="input-group my-2" role="search">
            <input class="form-control" name="q" type="search" placeholder="Search tasks" aria-label="Search"
                value="{{ Request::route()->getName() === 'project.task.search' ? Request::query('q', '') : '' }}">
            <button class="btn btn-outline-primary" type="submit"><i class="bi bi-search"></i></button>
        </form>

        @foreach ([['label' => 'Info', 'route' => 'project.info'], ['label' => 'Board', 'route' => 'project.board'], ['label' => 'Timeline', 'route' => 'project.timeline'], ['label' => 'Forum', 'route' => 'project.forum']] as $item)
            @include('partials.project.drawer.item', $item)
        @endforeach

        @if (Auth::user()->projects->contains($project))
            <a type="button" data-bs-toggle="modal" data-bs-target="#taskCreationModal"
                @class(['nav-link', 'nav-item'])>
                <i @class(['bi', 'bi-plus'])></i> Create Task
            </a>
        @endif
        <div class="modal" id="taskCreationModal" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Create task</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form method="@yield('method', 'POST')"
                            action="{{ route('project.task.new', ['project' => $project]) }}"
                            class="m-auto hstack gap-4 needs-validation p-3" id="task-creation-form" novalidate>
                            @csrf
                            @include('partials.project.task.new')
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" form="task-creation-form" class="btn btn-primary">Create</button>
                    </div>
                </div>
            </div>
        </div>

        @if (Auth::user()->id == $project->coordinator_id)
            <a href="{{ route('project.user.add', ['project' => $project]) }}" @class([
                'nav-link',
                'nav-item',
                'active' => Request::route()->getName() === 'project.user.add',
            ])>
                <i @class(['bi', 'bi-plus'])></i> Add User
            </a>
        @endif

    </nav>
</aside>
