<?php

// --------------------------
// Custom Backpack Routes
// --------------------------
// This route file is loaded automatically by Backpack\Base.
// Routes you generate using Backpack\Generators will be placed here.
Route::group([
    'prefix'     => config('backpack.base.route_prefix', 'admin'),
    'middleware' => ['web', 'admin'],
    'namespace'  => 'App\Http\Controllers',
], function () { // custom admin routes
    Route::crud('clients', 'Admin\ClientsCrudController');
    Route::crud('merchants', 'Admin\MerchantsCrudController');
});

Route::group([
    'prefix'     => config('backpack.base.route_prefix', 'admin'),
    'middleware' => ['web', 'admin'],
    'namespace'  => 'App\Actions',
], function () { // custom admin action routes
    Route::post('user/details', 'Users\UpdatePersonalInfo');
    Route::post('user/image', 'Users\UpdateProfileImage');
    Route::post('user/image/upload', 'Users\UploadProfileImage');
    Route::get('payment-gateways/{uuid}/manage', 'PaymentGateways\ManageClientEnabledGateway');
    Route::post('payment-gateways/{uuid}/manage/assign', 'PaymentGateways\AssignClientEnabledGatewayToShops');
    Route::post('payment-gateways/{uuid}/manage/enable', 'PaymentGateways\EnableClientGateway');

    Route::post('sms-providers/feature/enable', 'SMSProviders\EnableSMSFeatureForClient');
    Route::post('sms-providers/feature/disable', 'SMSProviders\DisableSMSFeatureForClient');
    Route::get('sms-providers/{uuid}/manage', 'SMSProviders\ManageClientEnabledSMS');
    Route::post('sms-providers/{uuid}/manage/assign', 'SMSProviders\AssignClientEnabledSMSToShops');
    Route::post('sms-providers/{uuid}/manage/enable', 'SMSProviders\EnableClientSMS');

    Route::get('product-catalog/{uuid}/shopify/import', 'Shopify\Products\ImportShopifyProducts');

    Route::post('checkout-funnels/products', 'CheckoutFunnels\LoadProducts');

    Route::get('checkout-themes', 'ComingSoon');
    Route::get('checkout-plugin', 'ComingSoon');
    Route::get('sales-reports', 'ComingSoon');
    Route::get('product-catalog', 'ComingSoon');
    Route::get('customers', 'ComingSoon');
    Route::get('orders', 'ComingSoon');
});

Route::group([
    'prefix'     => config('backpack.base.route_prefix', 'admin'),
    'middleware' => ['web', 'admin', 'membership'],
    'namespace'  => 'App\Http\Controllers',
], function () { // custom admin routes
    Route::get('dashboard', 'HomeController@dashboard');
    Route::get('shop-dashboard/{shop_id}', 'Dashboards\ShopDashboard@index');
    Route::get('merchant-dashboard/{merchant_id}', 'Dashboards\MerchantDashboard@index');
});

Route::group([
    'prefix'     => config('backpack.base.route_prefix', 'admin'),
    'middleware' => ['web', 'admin', 'membership'],
    'namespace'  => 'App\Http\Controllers\Admin',
], function () { // custom admin routes
    Route::get('user/resend-email/{userId}', 'CSVImportController@resend_email');
    Route::crud('user', 'UserCrudController');

    Route::crud('iconsset', 'IconsSetCrudController');
    Route::crud('shops', 'ShopsCrudController');

    Route::crud('payment-gateways', 'Features\GatewayProvidersCrudController');
    Route::crud('sms-providers', 'Features\SMSProvidersCrudController');
    Route::crud('checkout-funnels', 'Features\CheckoutFunnelsCrudController');
}); // this should be the absolute last line of this file
