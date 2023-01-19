@props(['body-class' => ''])

<x-layout.bare :$body-class>
    {{ $slot }}

    <footer id="footer">
        <nav>
            <a href="{{ route('static', ['name' => 'about']) }}">About us</a>
            <a href="{{ route('static', ['name' => 'faq']) }}">FAQ</a>
            <a href="{{ route('static', ['name' => 'contacts']) }}">Contacts</a>
            <a href="{{ route('static', ['name' => 'services']) }}">Services</a>
        </nav>
    </footer>
</x-layout.bare>
