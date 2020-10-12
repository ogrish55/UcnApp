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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});


// Route::apiResource('Users', \App\Http\Controllers\API\UserController::class);
Route::post('users', 'App\Http\Controllers\UserController@post');
Route::get('users', 'App\Http\Controllers\UserController@get');
Route::get('users/{id}', 'App\Http\Controllers\UserController@getSingle');
Route::delete('users/{id}', 'App\Http\Controllers\UserController@delete');
Route::put('users/{id}', 'App\Http\Controllers\UserController@update');
Route::get('data', 'App\Http\Controllers\DataController@GetData');
Route::get('data/{id}', 'App\Http\Controllers\DataController@GetDataUser');
Route::get('data/{id}/average', 'App\Http\Controllers\DataController@GetMonthlyAverage');
Route::get('data/{id}/consumption', 'App\Http\Controllers\DataController@GetMonthlyConsumption');
Route::get('data/{id}/monthlyMeasurements', 'App\Http\Controllers\DataController@GetMonthlyMeasurements');
Route::get('measurement/{id}', 'App\Http\Controllers\MeasurementController@measurement');

