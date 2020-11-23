@extends('shopify.layouts.shopify-embedded-app', ['shop'=> $shop])

@section('extra-header-stuff')
<style>
    @media screen {
        .content {
            height: 100%;
            width: 100%;
            margin: 0;
        }

        .py-4 {
            margin: 0 !important;
        }
    }
</style>
<script type="text/javascript">
    let TitleBar = actions.TitleBar;
    let Button = actions.Button;
    let Redirect = actions.Redirect;

    let breadcrumb = Button.create(shopifyApp, { label: '{!! $shop_name !!}' });
    /*
    breadcrumb.subscribe(Button.Action.CLICK, function() {
        shopifyApp.dispatch(Redirect.toApp({ path: '/breadcrumb-link' }));
    });
     */

    let titleBarOptions = {
        title: 'Account',
        breadcrumbs: breadcrumb
    };

    let myTitleBar = TitleBar.create(shopifyApp, titleBarOptions);
</script>
@endsection

@section('content')
    <div class="content">
        {{--
        <shopify-account-dashboard
            shop="{!! $shop !!}"
            :inventory="{{ json_encode($inventory) }}"
            :hmac="{{ json_encode($hmac) }}"
            :funnel="{{ json_encode($funnel) }}"
            api-key="{!! env('SHOPIFY_SALES_CHANNEL_API_KEY') !!}"
            redirect-uri="{!! env('APP_URL').'/sales-channel/dashboard' !!}"
        ></shopify-account-dashboard>
        --}}
    </div>
@endsection

@section('extra-footer-stuff')

@endsection
