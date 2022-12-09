@extends('layouts.project')

@section('project-content')
    @php
        echo $project->threads()->get();
    @endphp
    <section>
        <a href="" @class([
            'btn',
            'btn-primary'
        ])>
            <i @class([
                'bi',
                'bi-plus'
            ]) ></i> New Topic
        </a>
    </section>
    <section>

    </section>
@endsection
