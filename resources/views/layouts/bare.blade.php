<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" class="h-100">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @hasSection('title')
        <title>@yield('title') - {{ config('app.name', 'Laravel') }}</title>
    @else
        <title>{{ config('app.name', 'Laravel') }}</title>
    @endif

    {{-- Generated using https://realfavicongenerator.net/ --}}
    <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32"
        href="/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16"
        href="/favicon-16x16.png">
    <link rel="manifest" href="/site.webmanifest">
    <link rel="mask-icon" href="/safari-pinned-tab.svg" color="#6f42c1">
    <meta name="msapplication-TileColor" content="#6f42c1">
    <meta name="theme-color" content="#6f42c1">

    <script type="text/javascript">
        // Fix for Firefox autofocus CSS bug
        // See: http://stackoverflow.com/questions/18943276/html-5-autofocus-messes-up-css-loading/18945951#18945951
    </script>
    @vite(['resources/assets/sass/app.scss', 'resources/assets/ts/app.ts'])

    @stack('templates')
    <template id="toast-template">
        <div class="toast d-flex align-items-center" role="alert"
            aria-live="assertive" aria-atomic="true">
            <div class="toast-body" data-render-text="text"></div>
            <button type="button" class="btn-close m-3" data-bs-dismiss="toast"
                aria-label="Close"></button>
        </div>
    </template>
</head>

<body class="h-100 d-flex flex-column @stack('body-classes')">
    @yield('body')

    <div class="toast-container position-fixed bottom-0 end-0 p-3">
    </div>
    @include('cookie-consent::index')
</body>

</html>
