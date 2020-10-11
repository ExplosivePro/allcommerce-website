<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use AnchorCMS\Merchants;
use AnchorCMS\UserActiveClients;

Route::get('/', 'HomeController@home');

Route::get('/login', function () {
    return redirect('access/login');
});

Route::get('/home', function () {
    return redirect('access/dashboard');
});

Route::get('/switch/{client_id}', function ($client_id) {
    if(backpack_user()->isHostUser()) {
        if(backpack_user()->client_id == $client_id)
        {
            session()->forget('active_client');
            session()->forget('active_merchant');
            $sesh = UserActiveClients::whereUserId(backpack_user()->id)->delete();
        }
        else
        {
            session()->put('active_client', $client_id);
            session()->forget('active_merchant');

            $sesh = UserActiveClients::whereUserId(backpack_user()->id)->first();

            if(!is_null($sesh))
            {
                $sesh->client_id = $client_id;
                $sesh->save();
            }
            else
            {
                $sesh = new UserActiveClients();
                $sesh->user_id = backpack_user()->id;
                $sesh->client_id = $client_id;
                $sesh->save();
            }
        }
    }
    else
    {
        session()->forget('active_merchant');
    }

    return redirect(url()->previous());
});

Route::get('/merchant-switch/{merchant_id}', function ($merchant_id) {
    $merchant = Merchants::find($merchant_id);

    if(!is_null($merchant))
    {
        // Validate the active-client to the merchant.
        $active_client_id = backpack_user()->getActiveClientId();
        if($active_client_id == $merchant->client_id)
        {
            session()->put('active_merchant', $merchant_id);
        }
        else
        {
            session()->forget('active_merchant');
            return view('errors.500');
        }

        return redirect(url()->previous());
    }
    else
    {
        return view('errors.500');
    }
});

Route::get('/registration',  'UserRegistrationController@render_complete_registration');
Route::post('/registration', 'UserRegistrationController@complete_registration');

/**
 * Shopify App Goodness
 */
Route::group(['prefix' => 'shopify'], function () {
    Route::group(['prefix' => 'merchant'], function () {
        Route::get('/account', 'Shopify\ShopifyAccessController@account');

        Route::group(['prefix' => 'app'], function () {
            Route::get('/install', 'Shopify\ShopifyAccessController@app_install');
        });
    });

    Route::group(['prefix' => 'sales-channel'], function () {
        Route::get('/dashboard', 'Shopify\ShopifyAccessController@dashboard');

        Route::group(['prefix' => 'sales'], function () {
            // This is that f@#$ing sweetness, dat CHECKOUT PAGE, BIATCH!
            // @todo - buy shit.
            Route::get('/secure/checkout/{token}', 'Shopify\ShopifyCheckoutController@checkout');
        });
    });
});
