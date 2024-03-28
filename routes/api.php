<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::group(['namespace' => 'App\Http\Controllers\Auth','prefix' => 'auth'], function () {
    Route::post('login', 'LoginRegisterController@login')->name('login');
    Route::post('register', 'LoginRegisterController@register')->name('register');
    Route::post('logout', 'LoginRegisterController@logout');
    Route::get('test', 'LoginRegisterController@test');
});
Route::group(['middleware' => 'jwt.auth'], function () {
    Route::post('user', 'UserController@get_me')->name('get_me');
});
