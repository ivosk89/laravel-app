<?php

use Illuminate\Support\Facades\Route;

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

Route::post('login', 'API\LoginController@login')->name('login');
Route::post('register', 'API\RegisterController@register');

Route::group(['middleware' => 'auth:api'], function() {
    Route::get('logout', 'API\LoginController@logout');
    Route::get('products', 'API\ProductCatalogController@getAll');
});
