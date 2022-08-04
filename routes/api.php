<?php

use App\Http\Controllers\User\UserController;
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

Route::get('/users', [UserController::class, 'index']);

Route::post('/signup', [UserController::class, 'signUp']);

Route::get('/users/show/{id}', [UserController::class, 'show']);

Route::get('/profile', [UserController::class, 'show']);

Route::get('/profile/edit', [UserController::class, 'show']);

Route::get('/users/delete', [UserController::class, 'destroy']);
