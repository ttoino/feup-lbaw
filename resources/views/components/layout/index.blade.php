@props(['body-class' => '', 'main-class' => ''])

<x-layout.no-bar :$body-class>
    <x-navbar />

    <main class="d-flex flex-fill {{ $mainClasses }}">
        {{ $slot }}
    </main>
</x-layout.no-bar>
