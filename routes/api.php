<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\TransactionController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::resource('accounts', AccountController::class)->except(['create', 'edit']);
Route::resource('transactions', TransactionController::class)->except(['create', 'edit']);
