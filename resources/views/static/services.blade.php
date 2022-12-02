@extends('layouts.app')
@push('main-classes', 'flex-column align-items-center justify-content-center ')

@section('content')
<header class=static_page>
<h2><a href="/services">Main Features</a></h2>
</header>
<section class=static_page id="services_page">
  Atrellado provides you a lot of features to help you navigate the aplicaion that are described bellow: <br>
  <li>Once you are logged in this application you will be allowed to navigate through it.</li> 
  <li><b>Create a project</b>- You will see a page to create a project, where you can assigne other users to be 
  members of it.
  <li><b> Create a task </b>- You can also create tasks and organize them into groups (we seggest that the groups represent
  different criteria, such as priority, progress stage, etc.)</li>
  <li><b> About the task</b>- A task can be in one group at a time and you can change the group the task belongs to. 
  Each task can have comments made by project members. </li>
  <li><b> Forum</b>- Every project has a forum to allow project members to post messages and discuss many 
  topics related to the project directly in the application. </li>
  <li><b>Search</b>- To help you find what you want, most of the content is searchable, such as projects, tasks or users.</li>
  <li><b> Information</b>- In the bottom of the page you can find the way to contact us, where you can send us your feedback.
  Also, there's a <b><a href="/about">About us</a></b> page, where you'll find some information about this page and why 
  we did it. In addition, you can got to <b><a href="/faq">Frequent Questions Answers</a></b> page and see if 
  the question you have is already there and if not you can contact us in the <b><a href="/contacts">Contacts</a></b> page.


</section>

@endsection
