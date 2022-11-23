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
            <a href="{{ route('project.new') }}" class="btn btn-primary hstack gap-2">
                <i class="bi bi-plus"></i> Create project
            </a>
        </div>

        @include('partials.project.list')

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
