<?php

use App\Http\Controllers\Api\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UsersController;
use App\Http\Controllers\Api\CategoryController;

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('auth', [AuthController::class, 'login'])->name('login');
Route::post('register', [AuthController::class, 'register'])->name('register');
Route::middleware('auth:sanctum')->group(function () {
    Route::get('users/tokens', [UsersController::class, 'getAllTokens']);
    Route::delete('users/tokens', [UsersController::class, 'deleteAllTokens']);
    Route::delete('users/tokens/{token_id}', [UsersController::class, 'deleteToken']);

    Route::apiResources([
        'users' => UsersController::class,
        'categories' => CategoryController::class,
    ]);
});
