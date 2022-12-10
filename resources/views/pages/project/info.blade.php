@extends('layouts.project')

@section('title', $project->name)
@push('main-classes', 'overflow-auto')

@section('project-content')
    <section style="overflow-x: auto;" class="flex-fill d-flex flex-row gap-3 p-5 align-items-start flex-fill">
        <div class="col l-8">
            <section class="project-banner row">
                <h2 class="h2">{{ $project->name }}</h2>
                <p>{{ $project->description }}</p>
            </section>
            <section class="flex-fill d-flex flex-row gap-3">
                @php
                    use Carbon\Carbon;
                    
                    $creation_date = Carbon::parse($project->creation_date);
                    $last_modification_date = Carbon::parse($project->last_modification_date);
                @endphp
                <p><i class="bi bi-calendar mx-1"></i>Creation date: {{ $creation_date->diffForHumans(['aUnit' => true]) }}</p>
                @if ($project->last_modification_date !== null)
                    <p>Last edited: {{ $last_modification_date->diffForHumans(['aUnit' => true]) }}</p>
                @endif
            </section>
            <section class="flex-fill d-flex flex-row gap-3" style="max-width: 50%">
                <form method="POST" action="{{route('project.leave', ['project' => $project])}}">
                    @csrf
                    <button class="submit col btn btn-outline-danger">Leave project</button>
                </form>
                @if (Request::user()->id === $project->coordinator)
                    <a href="" class="col btn btn-outline-secondary">Archive project</a>
                @endif
            </section>
        </div>
        <div class="col l-4">
            <section class="user-list">
                <h2 class="h2">Project members</h2>
                @include('partials.list', ['paginator' => $project->users, 'itemView' => 'partials.list-item.user'])
            </section>
        </div>
    </section>
@endsection
