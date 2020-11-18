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

Route::middleware('auth:api')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::post('/logout', 'App\Http\Controllers\AuthController@logout');
    Route::patch('/updateUser', 'App\Http\Controllers\UserController@UpdateUser');
    Route::get('data/monthlyMeasurements/{type}', 'App\Http\Controllers\DataController@GetMonthlyMeasurements');
    Route::get('data/consumption/{type}/{returnType}', 'App\Http\Controllers\DataController@GetMonthlyConsumption');
    Route::get('data/consumption/combined/', 'App\Http\Controllers\DataController@GetMonthlyConsumptionCombined');
    Route::get('data/currentYearUsage/total/monthNumber', 'App\Http\Controllers\DataController@GetMonthNumber');
    Route::get('data/currentYearUsage/total/', 'App\Http\Controllers\DataController@GetLatestYearTotal');
    Route::get('data/currentYearUsage/{type}', 'App\Http\Controllers\DataController@GetLatestYear');
    Route::get('data/currentMonthUsage/total', 'App\Http\Controllers\DataController@GetLatestMonthTotal');
    Route::get('data/currentMonthUsage/{type}', 'App\Http\Controllers\DataController@GetLatestMonth');
    Route::get('data/average/{type}', 'App\Http\Controllers\DataController@GetMonthlyAverage');
    Route::get('data/monthlyUsageInDkk/', 'App\Http\Controllers\DataController@MonthlyUsageInDkk');

});

Route::post('/login', 'App\Http\Controllers\AuthController@login');
Route::post('/register', 'App\Http\Controllers\AuthController@register');
