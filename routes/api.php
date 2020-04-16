<?php

use Illuminate\Http\Request;
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

Route::group(['prefix' => 'v1'], function () {
    Route::post('/login', 'UsersController@login');
    Route::post('/register', 'UsersController@register');

    Route::group(['middleware' => 'auth:api'], function(){
        Route::get('/logout', 'UsersController@logout');
        Route::get('/details', 'API\UserController@details');
        Route::get('/user', function (Request $request) {
            return $request->user();
        });
    });
});
// Route::post('login', 'API\UserController@login');
// Route::post('register', 'API\UserController@register');

// Route::group(['middleware' => 'auth:api'], function(){
//     Route::post('details', 'API\UserController@details');
// });

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });
