<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;



Route::post('login', 'AuthController@login');
Route::post('register', 'AuthController@register');

Route::group(['middleware' => 'auth:api'], function(){
    Route::get('logout', 'AuthController@logout');
    Route::get('user', 'AuthController@user');
});
