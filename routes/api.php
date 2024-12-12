<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/register', [App\Http\Controllers\UsersController::class, 'register']);
Route::post('/login', [App\Http\Controllers\UsersController::class, 'login']);


Route::middleware('auth:api')->group(function () {

    // Route::post('/reLogin', [App\Http\Controllers\UsersController::class, 'reLogin']);
    Route::put('/update', [App\Http\Controllers\UsersController::class, 'update']);
    Route::post('/delete', [App\Http\Controllers\UsersController::class, 'destroy']);
   
});


