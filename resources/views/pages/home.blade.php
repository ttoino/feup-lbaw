@auth
    @include('pages.home.authenticated')
@endauth

@guest
    @include('pages.home.guest')
@endguest
