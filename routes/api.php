<?php

use App\Http\Controllers\Api\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UsersController;
use App\Http\Controllers\Api\CategoryController;

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('auth', [AuthController::class, 'login']);
Route::middleware('auth:sanctum')->group(function () {
    Route::apiResources([
        'users' => UsersController::class,
        'categories' => CategoryController::class,
    ]);
});
