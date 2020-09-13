<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}  | Cape & Bay </title>

    <!-- Scripts -->
    <script src="{{ asset('js/shopify-app.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/shopify-app.css') }}" rel="stylesheet">
    <style>
        html, body {
            width: 100%;
            height: 100%;
            margin: 0;
        }

        #app {
            font-family: semplicitapro, sans-serif;
        }

        header, footer {
            width: 100%;
            background-color: #2D2D2D;
        }
    </style>
    @yield('extra-header-stuff')
</head>
<body>
    <div>
        <div id="app">
            @include('includes.header')
            <main class="py-4">
                @yield('content')
            </main>
            @include('includes.footer')
        </div>
        @yield('extra-footer-stuff')
    </div>
</body>
</html>
