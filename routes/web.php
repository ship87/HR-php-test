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

Route::get('/', 'Controller@index')->name('index');

Route::group([
    'namespace' => 'Admin',
    'prefix' => 'admin',
    'as' => 'admin.',
], function () {
    Route::get('/orders', 'OrderController@index')->name('order.index');
    Route::get('/orders/{orderId}', 'OrderController@orderForm')->name('order.form');
    Route::post('/orders/{orderId}', 'OrderController@orderFormSave')->name('order.form.save');
    Route::get('/products', 'ProductController@index')->name('product.index');
    Route::post('/products/update-price', 'ProductController@updatePrice')->name('product.update-price');
});

Route::group([
    'namespace' => 'Client',
    'as' => 'client.',
], function () {
    Route::get('/temperature', 'TemperatureController@index')->name('temperature.index');
});
