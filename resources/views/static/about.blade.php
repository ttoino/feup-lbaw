@extends('layouts.app')
@push('main-classes', 'flex-column align-items-center justify-content-center ')

@section('content')
<header class=static_page>
  <h2><a href="/about">About Us</a></h2>
</header>
<section class=static_page id="about_page">
        If you are looking to boost your efficiency in managing your projects without the need to manually 
        keep track of what needs
        be done, we can help you. <br>
        <a href={{route('home')}}><strong class='text-primary'>Atrellado</strong></a> is a project developed by a small group of students as a product targeted at teams that 
        want to manage projects effectively. 
</section>       
    
@endsection
