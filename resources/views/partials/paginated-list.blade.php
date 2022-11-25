@if ($paginator->isEmpty())
    @yield('empty-list')
@else
    @yield('list-title')

    @include('partials.list')

    {{ $paginator->links() }}
@endif
