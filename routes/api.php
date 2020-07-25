<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Response;
 
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
    Route::post('/login', 'API\UserController@login');
    Route::post('/register', 'API\UserController@register');

        Route::get('/webhook' , function(){
        })->middleware("webhook");
        //testing api

    Route::group(['middleware' => ['auth:api','role:admin']], function () {



        Route::post('/lock/{parking_no}', 'TransactionController@lock');
        Route::resources([
            'parking' => 'ParkingController',
            'feecategory' => 'FeeCategoryController'
        ]);
    });
    Route::group(['middleware' => ['auth:api','role:viewer']], function () {
        Route::resource('parking','ParkingController')->only(["index","show"]);
        Route::resource('feecategory','FeeCategoryController')->only(["index","show"]);
    });



    //
    Route::post('authenticate', 'API\DashboardAuthenticateController@authenticate');

    Route::group(['middleware' => 'auth:api'], function(){
        Route::get('/logout', 'API\UserController@logout');
        Route::get('/transactions/{parking_no}', 'TransactionController@getTransaction');
        Route::get('/processpayment/{parking_no}', 'TransactionController@processPayment');
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
