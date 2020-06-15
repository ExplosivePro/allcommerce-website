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

Route::get('/', 'HomeController@home');

Route::group(['prefix' => 'access'], function () {
    Route::get('/', 'PlatformAccessController@index')->name('login');
    Route::post('/', 'PlatformAccessController@token')->name('login');
    Route::get('/logout', 'PlatformAccessController@logout')->name('logout');

    Route::group(['middleware' => ['allcommerce']], function () {
        Route::get('/dashboard', 'HomeController@index')->name('dashboard');
        Route::post('/dashboard', 'HomeController@merchant_selected')->name('merchant_selected');
        Route::post('/switch', 'HomeController@reset_merchant_selection')->name('merchant_reset');
        Route::get('/merchandise', 'MerchMgntController@index')->name('merchandise');
    });
});

/**
 * Shopify Sales Channel ish
 */
Route::group(['prefix' => 'shopify'], function () {
    Route::group(['prefix' => 'merchant'], function () {
        Route::get('/account', 'ShopifyAccessController@account');
        Route::group(['prefix' => 'app'], function () {
            Route::get('/install', 'ShopifyAccessController@app_install');
        });
    });

    Route::group(['prefix' => 'sales-channel'], function () {
        Route::get('/dashboard', 'ShopifyAccessController@dashboard');

        Route::group(['prefix' => 'sales'], function () {
            Route::get('/secure/checkout/{token}', 'ShopifyCheckoutController@checkout');
        });

    });
    Route::group(['prefix' => 'oauth'], function () {

    });
});

