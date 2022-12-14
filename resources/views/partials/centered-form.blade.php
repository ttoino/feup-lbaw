<form 
    method="@yield('method', 'POST')" 
    action="@yield('action', route(Route::current()->getName(), Route::current()->parameters))"
    enctype="@yield('enctype', 'multipart/form-data')"
    class="m-auto vstack gap-3 needs-validation p-3" style="max-width: 480px"
    novalidate>
    @csrf
    @yield('form')
</form>
