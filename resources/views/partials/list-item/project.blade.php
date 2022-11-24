<li class="list-group-item list-group-item-action position-relative d-flex flex-row align-items-center"
    data-project-id="{{ $item->id }}">
    <div class="vstack flex-fill">
        <a href="{{ route('project.home', ['id' => $item->id]) }}"
            class="stretched-link fw-bold">
            {{ $item->name }}
        </a>
        <span>Coordinator:
            {{ $item->coordinator()->first()->name }}</span>
    </div>

    @section('right-side')
        <button class="btn btn-outline" style="z-index: 5"><i
                class="bi bi-heart"></i></button>
    @show
</li>
