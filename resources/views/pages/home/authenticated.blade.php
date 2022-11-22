@extends('layouts.app')

@push('main-classes', 'flex-column p-3 container gap-3')

@section('content')

    @empty($projects)
        <p>You don't have any projects yet!</p>
        <a href="{{ route('project.new') }}" class="btn btn-primary"><i
                class="bi bi-plus"></i> Create your first</a>
    @else
        <div class="hstack justify-content-between">
            <h2>Your projects</h2>
            <button class="btn btn-primary hstack gap-2">
                <i class="bi bi-plus"></i> Create project
            </button>
        </div>

        <ul class="list-group">
            @foreach ($projects as $project)
                <li
                    class="list-group-item list-group-item-action position-relative d-flex flex-row align-items-center">
                    <div class="vstack flex-fill">
                        <a href="{{ route('project.home', ['id' => $project->id]) }}"
                            class="stretched-link fw-bold"
                            style="color: inherit; text-decoration: inherit">
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

        {{-- TODO --}}
        {{-- <nav aria-label="Projects pagination" class="align-self-center">
            <ul class="pagination">
                <li class="page-item"><a class="page-link" href="#"><i
                            class="bi bi-caret-left-fill"></i></a>
                </li>
                <li class="page-item"><a class="page-link" href="#">1</a></li>
                <li class="page-item"><a class="page-link" href="#">2</a></li>
                <li class="page-item"><a class="page-link" href="#">3</a></li>
                <li class="page-item"><a class="page-link" href="#"><i
                            class="bi bi-caret-right-fill"></i></a></li>
            </ul>
        </nav> --}}
    @endempty
@endsection
