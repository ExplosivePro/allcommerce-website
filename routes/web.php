<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/', 'App\Http\Controllers\HomeController@home');

Route::get('/registration',  'App\Http\Controllers\UserRegistrationController@render_complete_registration');
Route::post('/registration', 'App\Http\Controllers\UserRegistrationController@complete_registration');

/**
 * Shopify App Goodness
 */
Route::group(['prefix' => 'shopify'], function () {
    Route::group(['prefix' => 'merchant'], function () {
        Route::get('/account', 'App\Http\Controllers\Shopify\ShopifyAccessController@account');

        Route::group(['prefix' => 'app'], function () {
            Route::get('/install', 'App\Http\Controllers\Shopify\ShopifyAccessController@app_install');
        });
    });
});
