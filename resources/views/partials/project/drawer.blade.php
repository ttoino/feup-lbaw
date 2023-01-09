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

        @foreach ([
            ['icon' => 'info-circle', 'label' => 'Info', 'route' => 'project.info'],
            ['icon' => 'people', 'label' => 'Members', 'route' => 'project.members'],
            ['icon' => 'tags', 'label' => 'Tags', 'route' => 'project.tags'],
            ['icon' => 'kanban', 'label' => 'Board', 'route' => ['project.board', 'project.task.info']],
            ['icon' => 'list-check', 'label' => 'Tasks', 'route' => 'project.tasks'],
            ['icon' => 'bezier2', 'label' => 'Timeline', 'route' => 'project.timeline'],
            ['icon' => 'chat-left-text', 'label' => 'Forum', 'route' => ['project.forum', 'project.thread']]] as $item)
            @include('partials.project.drawer.item', $item)
        @endforeach

    </nav>
</aside>
