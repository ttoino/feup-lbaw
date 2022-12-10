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

    <script type="text/javascript">
        // Fix for Firefox autofocus CSS bug
        // See: http://stackoverflow.com/questions/18943276/html-5-autofocus-messes-up-css-loading/18945951#18945951
    </script>
    @vite(['resources/assets/sass/app.scss', 'resources/assets/ts/app.ts'])
</head>

<body class="h-100 d-flex flex-column @stack('body-classes')">
    @yield('body')

    <div class="toast-container position-fixed bottom-0 end-0 p-3">
        <div id="liveToast" class="toast" role="alert" aria-live="assertive"
            aria-atomic="true">
            <div class="d-flex align-items-center">
                <div class="toast-body"></div>
                <button type="button" class="btn-close m-3"
                    data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>
    </div>
</body>

</html>
