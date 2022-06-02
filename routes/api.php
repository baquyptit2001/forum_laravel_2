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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return \App\Http\Resources\UserResource::make($request->user());
});

Route::group(['prefix' => 'user'], function () {
    Route::post('/sign-up', [\App\Http\Controllers\User\AuthController::class, 'register'])->name('user.sign-up');
    Route::post('/sign-in', [\App\Http\Controllers\User\AuthController::class, 'login'])->name('user.sign-in');
});
