<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ env('APP_NAME', 'Laravel') }}  | Cape & Bay </title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js').'?v='.date('mis') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <style>
        html, body, #app {
            width: 100%;
            height: 100%;
            margin: 0;
        }
    </style>
    <script src="https://unpkg.com/@shopify/app-bridge/umd"></script>
    <script>
        let permissionUrl = 'https://{!! $shop !!}/admin/oauth/authorize?client_id={!! env('SHOPIFY_SALES_CHANNEL_API_KEY') !!}&scope=read_products,read_content&redirect_uri={!! env('APP_URL').'/sales-channel/dashboard' !!}';
        let AppBridge = window['app-bridge'];
        let actions = window['app-bridge'].actions;
        let createApp = AppBridge.default;

        let shopifyApp = createApp({
            apiKey: '{!! env('SHOPIFY_SALES_CHANNEL_API_KEY') !!}',
            shopOrigin: '{!! $shop !!}',
        });

        console.log('ShopifyApp - ', shopifyApp)
    </script>
    @yield('extra-header-stuff')
</head>
<body>
    <div id="app">
        <main class="py-4">
            @yield('content')
        </main>
    </div>


    @yield('extra-footer-stuff')
</body>
</html>
