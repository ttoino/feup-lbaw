@extends('layouts.app')
@push('main-classes', 'flex-column align-items-center justify-content-center ')

@section('content')
<header class=static_page>
  <h2><a href="/contact">Contact</a></h2>
</header>
<section class=static_page id="contact_page">
  We are ready to help with whatever you need. Contact us:
  @if (Auth::check())
    <form class="contact">
      <input class="form-control" style="width: 50em;" type="text" name="contact" placeholder="Message">
      <input type="submit" class="btn btn-primary" value="Send">
    </form>
  @else
    <br>
    <a href="/login" style="color:#ff3333">Please login first</a>
  @endif

</section>
@endsection
