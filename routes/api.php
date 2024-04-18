<?php

use Illuminate\Support\Facades\Route;

Route::group(['namespace' => 'App\Http\Controllers\Auth','prefix' => 'auth'], function () {
    Route::post('login', 'LoginRegisterController@login')->name('login');
    Route::post('register', 'LoginRegisterController@register')->name('register');
    Route::post('logout', 'LoginRegisterController@logout');
    Route::get('test', 'LoginRegisterController@test');
});
Route::group(['middleware' => 'jwt_auth'], function () {
    Route::group(['namespace' => 'App\Http\Controllers', 'middleware' => 'api'], function () {
        Route::get('get_me', 'UserController@get_me')->name('get_me');
        Route::group(['prefix' => 'admin','namespace' => 'Admin'], function () {
            Route::group(['prefix' => 'users'], function () {
                Route::post('{user}', 'AccountController@update');
            });
            Route::group(['prefix' => 'products'], function () {
                Route::get('/', 'ProductController@index');
                Route::post('/store', 'ProductController@store');
            });
            Route::group(['prefix' => 'categories'], function () {
                Route::get('/', 'CategoryController@index');
            });
        });

    });
});
