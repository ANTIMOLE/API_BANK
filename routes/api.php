<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::post('/register', [App\Http\Controllers\UsersController::class, 'register']);
Route::post('/login', [App\Http\Controllers\UsersController::class, 'login']);


Route::middleware('auth:api')->group(function () {

    // Route::post('/reLogin', [App\Http\Controllers\UsersController::class, 'reLogin']);
    Route::put('/update', [App\Http\Controllers\UsersController::class, 'update']);
    Route::post('/delete', [App\Http\Controllers\UsersController::class, 'destroy']);

    Route::post('/show/account',[App\Http\Controllers\AccountsController::class,'show']);
    Route::post('/update/account',[App\Http\Controllers\AccountsController::class,'update']);

    Route::get('/index/transaction',[App\Http\Controllers\TransactionsController::class,'index']);

    Route::post('/transaction/store',[App\Http\Controllers\TransactionsController::class,'store']);
    Route::post('/transaction/loan/store',[App\Http\Controllers\LoansController::class,'update']);


    Route::post('/transaction/showWithLoans',[App\Http\Controllers\TransactionsController::class,'showAllTransactionWithLoan']);
    Route::post('/transaction/show',[App\Http\Controllers\TransactionsController::class,'show']);
    Route::post('/transaction/showByUser',[App\Http\Controllers\TransactionsController::class,'showByUser']);
    Route::post('/transaction/destroy',[App\Http\Controllers\TransactionsController::class,'destroy']);
    Route::post('/transaction/changeStatus',[App\Http\Controllers\TransactionsController::class,'changeStatus']);
   
});


