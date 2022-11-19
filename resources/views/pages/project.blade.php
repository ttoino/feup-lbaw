@extends('layouts.app')
@section('title', $project->name)

@section('content')
    <div class='row'>
        @include('partials.sidemenu')
        <div class="col-10">
            <div class="row">
                @foreach ($project->taskGroups as $g)
                    <div class='col border border-primary m-3 p-3 text-center'>
                        <p>{{$g->name}}</p>
                        @foreach ($g->tasks as $t)
                            <p class="border border-primary p-1">{{$t->name}}</p> {{-- change this later to redirect to the task url (need to have taskID) --}}
                        @endforeach
                        <a href="{{ url('/project/' . $id . '/task/new') }}" class="border border-primary p-1">&plus; Create task</a> {{-- change this later to route() once it's defined --}}
                    </div>
                @endforeach
                <div class='col border border-primary m-3 p-3 text-center'>
                    <a href="{{ url('/project/' . $id) }}">&plus; Create group</a> {{-- change referral when OpenApi is updated --}}
                </div>    
            </div>
        </div>
    </div>
@endsection
