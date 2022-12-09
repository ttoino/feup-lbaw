<form method="@yield('method', 'POST')" action="@yield('action', route(Route::current()->getName(), Route::current()->parameters))"
    class="m-auto vstack gap-3 needs-validation p-3" style="max-width: 480px"
    novalidate>
    @yield('form')
</form>
