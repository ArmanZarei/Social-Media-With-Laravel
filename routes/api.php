<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\AuthController;
use \App\Http\Controllers\ForgotPasswordController;

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

Route::get('hello', [\App\Http\Controllers\HelloController::class, 'index']);
Route::post('login', [AuthController::class, 'login']);
Route::get('user', [AuthController::class, 'user'])->middleware('auth:api');
Route::post('register', [AuthController::class, 'register']);

Route::post('forgot', [ForgotPasswordController::class, 'forgot']);
Route::post('reset', [ForgotPasswordController::class, 'reset']);
