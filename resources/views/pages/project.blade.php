@extends('layouts.app')
@section('title', $project->name)

@section('content')
    <div class='row'>
        @include('partials.sidemenu')
        <div class="col-10">
            <div class="row">
                @foreach ($project->taskGroups as $g)
                    <div class='col border border-secondary m-3 p-3 text-center d-flex flex-column'>
                        <p>{{ $g->name }}</p>
                        @foreach ($g->tasks as $t)
                            <a href="{{ url('/project/' . $id . '/task/' . $t->id)}}"
                            class="border border-secondary p-1 mb-3">{{ $t->name }}</a> 
                        @endforeach
                        <a href="{{ url('/project/' . $id . '/task/new') }}"
                            class="border border-secondary p-1">&plus; Create task</a>
                    </div>
                @endforeach
                <div class='col border border-secondary m-3 p-3 text-center'>
                    <a href="{{ route('project.home', ['id' => $id]) }}">&plus; Create group</a>
                    {{-- change referral when OpenApi is updated --}}
                </div>
            </div>
        </div>
    </div>
@endsection
