@extends('layouts.app')

@section('content')
    <div class="d-flex flex-column narrow mx-auto my-auto p-3">
        <header>
            <h2><a href="/about">About Us</a></h2>
        </header>
        <section id="about_page">
            If you are looking to boost your efficiency in managing your projects
            without the need to manually
            keep track of what needs
            be done, we can help you. <br>
            <a href={{ route('home') }}><strong class='text-primary'>Atrellado</strong></a> is a project
            developed by
            a small group of students as a product targeted at teams that
            want to manage projects effectively.
        </section>
    </div>
@endsection
