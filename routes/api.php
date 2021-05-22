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

/** @see AuthController::authenticate() */
Route::post('login', 'AuthController@authenticate');

Route::middleware('auth:api')->get('user', function (Request $request) {
    return $request->user();
});

Route::prefix('organisation')->group(function () {
    /** @see OrganisationController::listAll() */
    Route::middleware('auth:api')
        ->post('list-all', 'OrganisationController@listAll');
    /** @see OrganisationController::store() */
    Route::middleware('auth:api')
        ->post('store', 'OrganisationController@store');
});
