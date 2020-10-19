@if($entry->shop_type()->first()->name == 'Shopify')
    @if(\AllCommerce\ShopifyInstalls::isInstalled($entry->id))
        <a class="btn btn-xs btn-default" disabled>
            <i class="fab fa-shopify"></i> Shopify Connected
        </a>
    @else
        <a class="btn btn-xs btn-default" href="{!! env('APP_URL') !!}/?hmac=12345678923455426&shop={!! $entry->shop_url !!}&timestamp={!! strtotime('now') !!}" target="_blank">
            <i class="fab fa-shopify"></i> Connect to Shopify
        </a>
    @endif
@endif
