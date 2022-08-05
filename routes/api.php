<?php

use App\Http\Controllers\Cart\CartController;
use App\Http\Controllers\Comment\CommentController;
use App\Http\Controllers\Product\CategoryController;
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
    Route::put('products/edit/{id}', [ProductController::class, 'edit']);
    Route::get('products/delete/{id}', [ProductController::class, 'destroy']);

    Route::post('categories/create', [CategoryController::class, 'create']);
    Route::put('categories/edit/{id}', [CategoryController::class, 'edit']);
    Route::get('categories/delete/{id}', [CategoryController::class, 'destroy']);

    Route::post('/products/show/{id}/comments', [CommentController::class, 'create']);
    Route::get('/products/show/{product_id}/comments/{comment_id}/delete', [CommentController::class, 'destroy']);

    Route::get('/cart', [CartController::class, 'index']);
    Route::post('products/{id}/buy', [CartController::class, 'addToCart']);
    Route::get('products/{id}/return', [CartController::class, 'removeFromCart']);
    Route::get('/cart/submit', [CartController::class, 'submitCart']);
});

Route::get('/products', [ProductController::class, 'index']);
Route::get('/products/show/{id}', [ProductController::class, 'show']);

Route::get('/categories', [CategoryController::class, 'index']);
Route::get('/categories/{id}', [CategoryController::class, 'show']);

Route::get('/comments', [CommentController::class, 'index']);
Route::get('/products/show/{id}/comments', [CommentController::class, 'show']);

