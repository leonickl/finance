<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StatementController;
use App\Http\Controllers\TransactionController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/accounts', [AccountController::class, 'index'])->name('accounts');
    Route::get('/account/{id}', [AccountController::class, 'show'])->name('account');

    Route::get('/transactions', [TransactionController::class, 'index'])->name('transactions');
    Route::get('/transactions/create', [TransactionController::class, 'create'])->name('create-transaction');
    Route::get('/transactions/{id}', [TransactionController::class, 'show'])->name('transaction');
    Route::post('/transactions', [TransactionController::class, 'store'])->name('store-transaction');
    // Route::post('/transactions/{id}', [TransactionController::class, 'update'])->name('update-transaction');

    Route::get('/statement', [StatementController::class, 'index'])->name('statement');
});

require __DIR__.'/auth.php';
