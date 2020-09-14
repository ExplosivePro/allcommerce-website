<meta name="csrf-token" content="{{ csrf_token() }}" />

<!-- These below should be dynamic to the client -->
<link rel="icon" href="{!! asset('/img/icon.png') !!}" sizes="32x32">
<link rel="icon" href="{!! asset('/img/icon.png') !!}" sizes="192x192">
<link rel="apple-touch-icon-precomposed" href="{!! asset('/img/icon.png') !!}">

<link rel="stylesheet" href="{!! mix('css/shopify-app.css') !!}"/>

<style>
    @media screen {
        body {
            width: 100%;
            height: 100%;
            margin: 0;
        }

        #checkoutApp {
            width: 100%;
            margin: 0;
        }

        .inner-checkout-app {
            height: 100%;
            display: flex;
            flex-flow: column;
            align-items: center;
        }
    }

    @media screen and (max-width: 999px) {

    }

    @media screen and (min-width: 1000px) {

    }
</style>

@yield('before_styles')
@stack('before_styles')

