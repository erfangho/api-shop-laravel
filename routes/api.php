<?php

use App\Http\Controllers\Product\ProductController;
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

Route::post('/signup', [UserController::class, 'signUp']);
Route::post('/login', [UserController::class, 'login']);

Route::middleware('auth:sanctum')->group( function () {

    Route::get('/users', [UserController::class, 'index']);
    Route::get('/users/show/{id}', [UserController::class, 'show']);
    Route::get('/profile', [UserController::class, 'profile']);
    Route::get('/users/delete/{id}', [UserController::class, 'destroy']);
    Route::get('/logout', [UserController::class, 'logout']);

    Route::post('products/create', [ProductController::class, 'create']);
    Route::post('products/edit/{id}', [ProductController::class, 'edit']);
    Route::get('products/delete/{id}', [ProductController::class, 'destroy']);


});

Route::get('/products', [ProductController::class, 'index']);
Route::get('/products/show/{id}', [ProductController::class, 'show']);
