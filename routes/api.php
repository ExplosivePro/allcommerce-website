<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['prefix' => 'client'], function() {
    Route::get('{client_id}/mobile/{app_id}/notifications/push', 'API\Clients\MobileApps\Notifications\PushNotificationsAPIController@getFiltersFromClient');
    Route::post('{client_id}/mobile/{app_id}/notifications/push/users', 'API\Clients\MobileApps\Notifications\PushNotificationsAPIController@getUsersFromFilters');
    Route::post('{client_id}/mobile/{app_id}/notifications/push/fire', 'API\Clients\MobileApps\Notifications\PushNotificationsAPIController@fire');
    Route::get('{client_uuid}/budgets', 'API\Clients\Ads\ClientAdBudgetAPIController@get_all_budget_data');
    Route::get('{client_uuid}/budgets/market/{name}', 'API\Clients\Ads\ClientAdBudgetAPIController@get_budget_data_for_market');
    Route::get('{client_uuid}/budgets/club/{club_id}', 'API\Clients\Ads\ClientAdBudgetAPIController@get_budget_data_for_club');
});

Route::group(['prefix' => 'checkout'], function () {
    Route::group(['prefix' => 'shopify'], function () {
        Route::group(['prefix' => 'leads'], function () {
            /* DEPRECATED */
            Route::post('/', 'API\Checkouts\LeadsAPIController@_UNSUPPORTED_create_or_update_lead');

            Route::group(['prefix' => 'create'], function () {
                Route::post('/email', 'API\Checkouts\LeadsAPIController@create_lead_with_email');
                Route::post('/shipping', 'API\Checkouts\LeadsAPIController@create_lead_with_shipping');
                Route::post('/draft-order/sm', 'API\Checkouts\LeadsAPIController@draft_order_with_shipping_methods');
            });

            Route::group(['prefix' => 'update'], function () {
                Route::put('/email', 'API\Checkouts\LeadsAPIController@update_lead_with_email');
                Route::put('/shipping', 'API\Checkouts\LeadsAPIController@update_lead_with_shipping');
                Route::put('/billing', 'API\Checkouts\LeadsAPIController@update_lead_with_billing');
            });
        });
        Route::group(['prefix' => 'one-click'], function () {
            Route::post('/validate', 'API\Checkouts\OneClickCheckoutAPIController@validate_input');
            Route::post('/resend', 'API\Checkouts\OneClickCheckoutAPIController@resend_code');
        });
    });
});

Route::group(['prefix' => 'shopify'], function () {
    Route::post('/login', 'API\Shopify\ShopifyAccessAPIController@sales_channel_login_connect');
    Route::post('/inventory', 'API\Shopify\ShopifyAccessAPIController@inventory');
});
