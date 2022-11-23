<ul class="list-group">
    @foreach ($projects as $project)
        <li class="list-group-item list-group-item-action position-relative d-flex flex-row align-items-center" data-project-id="{{ $project->id }}">
            <div class="vstack flex-fill">
                <a href="{{ route('project.home', ['id' => $project->id]) }}"
                    class="stretched-link fw-bold">
                    {{ $project->name }}
                </a>
                <span>Coordinator:
                    {{ $project->coordinator()->first()->name }}</span>
            </div>

            <button class="btn btn-outline" style="z-index: 5"><i
                    class="bi bi-heart"></i></button>
        </li>
    @endforeach
</ul>