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

Route::get('/', 'IndexController@index')->name('index');

Route::group(['prefix' => 'login'], function () {
    Route::get('google', 'Auth\LoginController@redirect')->name('login.redirect');
    Route::get('google/callback', 'Auth\LoginController@callback')->name('login.callback');
});

Route::get('logout', 'Auth\LoginController@logout')->name('logout');

Route::group(['prefix' => 'item'], function () {
    Route::post('save', 'Items\AjaxController@save')->name('item.save');
    Route::post('update', 'Items\AjaxController@update')->name('item.update');
});