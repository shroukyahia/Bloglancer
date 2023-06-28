<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\api\auth\AuthController;
use App\Http\Controllers\api\user\PostController;
use App\Http\Controllers\api\user\CategoryController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('/user')->group(function () {

    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);


    Route::prefix('/post')->group(function () {
        Route::get('/index', [PostController::class, 'index']);
        Route::post('/store', [PostController::class, 'store'])->middleware('auth:sanctum');
        Route::get('/show/{id}', [PostController::class, 'show']);
        Route::post('/update/{id}', [PostController::class, 'update'])->middleware('auth:sanctum');
        Route::get('/delete/{id}', [PostController::class, 'destroy'])->middleware('auth:sanctum');

        Route::post('/assign/{id}', [PostController::class, 'assign_category_to_post'])->middleware('auth:sanctum');
        Route::post('/search', [PostController::class, 'search']);
    });

    Route::prefix('/category')->group(function () {
        Route::get('/index', [CategoryController::class, 'index']);
        Route::post('/store', [CategoryController::class, 'store']);
        Route::get('/show/{id}', [CategoryController::class, 'show']);
        Route::post('/update/{id}', [CategoryController::class, 'update']);
        Route::get('/delete/{id}', [CategoryController::class, 'destroy']);
    });
});
