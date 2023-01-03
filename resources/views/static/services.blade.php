@extends('layouts.app')

@section('content')
    <div class="d-flex flex-column narrow mx-auto my-auto p-3">
        <header class=static_page>
            <h2><a href="/services">Services</a></h2>
        </header>
        <section class=static_page id="services_page">
            Atrellado provides you a lot of features to help you navigate the site.
            <br>

            <li><strong>Create a project</strong> - You will see a page to create a
                project, where you can assign other users to participate.
            <li><strong> Create a task </strong> - You can also create tasks and
                organize
                them into groups (we suggest that the groups represent
                different criteria, such as priority, progress stage, etc.)</li>
            <li><strong>About the task</strong> - A task can be in one group at a
                time
                and you can
                change the group the task belongs to.
                Each task can have comments made by project members, tags to
                classify it
                and assigned team members. </li>
            <li><strong>Project Forum</strong> - Every project has a forum to allow
                project
                members to post messages and discuss many
                topics related to the project directly in the application. </li>
            <li><strong>Search</strong> - To help you find what you want, most of
                the
                content is
                searchable, such as projects, tasks or users.</li>
            <li><strong>Information</strong> - In the bottom of the page you can
                find
                the <a class="link-primary"
                    href={{ route('static', ['name' => 'about']) }}>About
                    us</a>
                page,
                where you'll find some information about this app.
                Also, you can check some <a class="link-primary"
                    href={{ route('static', ['name' => 'faq']) }}>Frequently Asked
                    Questions</a>
                and see if any question you might have is already there and if not
                you
                can contact us in the <a class="link-primary"
                    href={{ route('static', ['name' => 'contacts']) }}>Contacts</a>
                page.</li>
        </section>
    </div>
@endsection
