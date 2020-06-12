@extends('layouts.shopify-embedded-app', ['shop'=> $shop])

@section('extra-header-stuff')
<style>
    @media screen {
        .content {
            height: 100%;
            width: 100%;
            margin: 0;
        }
    }
</style>
<script type="text/javascript">
    let TitleBar = actions.TitleBar;
    let Button = actions.Button;
    let Redirect = actions.Redirect;

    let breadcrumb = Button.create(shopifyApp, { label: 'My breadcrumb' });
    breadcrumb.subscribe(Button.Action.CLICK, function() {
        shopifyApp.dispatch(Redirect.toApp({ path: '/breadcrumb-link' }));
    });

    let titleBarOptions = {
        title: 'My page title',
        breadcrumbs: breadcrumb
    };

    let myTitleBar = TitleBar.create(shopifyApp, titleBarOptions);
</script>
@endsection

@section('content')
    <div class="content">
        <shopify-polaris
            shop="{!! $shop !!}"
            api-key="{!! env('SHOPIFY_SALES_CHANNEL_API_KEY') !!}"
            redirect-uri="{!! env('APP_URL').'/sales-channel/dashboard' !!}"
        ></shopify-polaris>
    </div>
@endsection

@section('extra-footer-stuff')

@endsection
