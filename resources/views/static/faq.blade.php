@extends('layouts.app')

@section('content')
    <div class="d-flex flex-column narrow mx-auto my-auto p-3">
        <header>
            <h2><a href="/faq">Frequently Asked Questions</a></h2>
        </header>
        <section>
            <div class=question><strong>Why choose Atrellado?</strong></div>
            <div class=answer>We make it our daily job to provide the best
                experience
                with managing projects to anyone who might be interested in it.
            </div>
            <br>

            <div class=question><strong>How do I create a project?</strong></div>
            <div class=answer>To create a project, you must have an account on the
                platform.
                You can easily start a new project, all you have to do is go to <a href={{ route('project.list') }}
                    class='text-primary'>your
                    projects</a> and fill the form with essential
                information about it. Afterwards, you can edit it and organize it
                your
                way!</div><br>

            <div class=question><strong>How do I create a task group?</strong></div>
            <div class=answer>All you need to do is go to the project page, and
                look for "Create group". You will find a box where you can insert
                the
                name of the task group, click the button on the right, and it is
                done!
                Good work!</div><br>

            <div class=question><strong>How do I create a new task?</strong></div>
            <div class=answer>Once it's done all you need to do is go to the project
                page, (you need to have a group
                already created) and look for "Create task". You will find a box
                where
                you can insert the name of the task, then click on the blue button
                on
                the right, and it is done! Good work!</div><br>
        </section>
    </div>
@endsection
