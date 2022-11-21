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
            <ul class="dropdown-menu w-100">
                <li>
                    <a class="dropdown-item" href="#">Other project</a>
                </li>
                <li>
                    <a class="dropdown-item" href="#">Other project 2</a>
                </li>
                <li>
                    <a class="dropdown-item" href="#">Other project 3</a>
                </li>
            </ul>
        </div>
        @foreach ([['label' => 'Info', 'path' => "project/$id/info"], ['label' => 'Board', 'path' => "project/$id"], ['label' => 'Timeline', 'path' => "project/$id/timeline"], ['label' => 'Forum', 'path' => "project/$id/forum"]] as $item)
            @include('partials.project.drawer.item', $item)
        @endforeach
    </nav>
</aside>
