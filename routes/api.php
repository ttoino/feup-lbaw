<?php

use Illuminate\Http\Request;

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
// every route defined in this file should be inserted inside this group in order to automatically be registered as an API route
Route::middleware('api')->group(function () {
    Route::middleware('auth')->get('/user', 'Auth\LoginController@getUser');
});
