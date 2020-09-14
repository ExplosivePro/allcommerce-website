<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0, user-scalable=no">
        <meta http-equiv="cache-control" content="no-cache, must-revalidate, post-check=0, pre-check=0">
        <meta http-equiv="expires" content="0">
        <meta http-equiv="pragma" content="no-cache">
        <meta name="robots" content="noindex, nofollow">

        <title>{!! $shop_name !!} | Secure Checkout </title>

        @include('checkouts.includes.checkout-head')
    </head>
    <body>
        <div id="checkoutApp">
            <div class="inner-checkout-app">
                @yield('content')
            </div>
        </div>
        @yield('before_scripts')
        @stack('before_scripts')

        @include('checkouts.includes.checkout-scripts')

        @yield('after_scripts')
        @stack('after_scripts')
    </body>
</html>
