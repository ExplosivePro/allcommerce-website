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

Route::get('/', function () {
    return view('welcome');
});

Route::group(['prefix' => 'access'], function () {
    Route::get('/', 'PlatformAccessController@index')->name('login');
    Route::post('/', 'PlatformAccessController@token')->name('login');
    Route::get('/logout', 'PlatformAccessController@logout')->name('logout');

    Route::group(['middleware' => ['allcommerce']], function () {
        Route::get('/dashboard', 'HomeController@index')->name('dashboard');
        Route::get('/merchandise', 'MerchMgntController@index')->name('merchandise');
    });
});

/**
 * Shopify Sales Channel ish
 */
Route::group(['prefix' => 'shopify'], function () {

    Route::group(['prefix' => 'sales-channel'], function () {
        Route::get('/checkout', 'ShopifyCheckoutController@checkout');
    });
});

