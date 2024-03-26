<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::group(['namespace' => 'App\Http\Controllers\Auth','prefix' => 'auth'], function () {
    Route::post('login', 'LoginRegisterController@login');
    Route::post('register', 'LoginRegisterController@register');
    Route::post('logout', 'LoginRegisterController@logout');
});
Route::get('/user', function (Request $request) {
    return $request->user() ? [
        'success'=>true,
        'data'=>$request->user()?->FilterApiSelect()
    ] : [
        'success'=>false,
        'data'=>[]
    ];
})->middleware('auth:sanctum');