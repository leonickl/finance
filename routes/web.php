<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\BankController;
use App\Http\Controllers\BankProposalController;
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
    Route::patch('/transactions/{id}', [TransactionController::class, 'update'])->name('transaction.patch');

    Route::get('/statement', [StatementController::class, 'index'])->name('statement');

    Route::get('/bank', [BankController::class, 'index'])->name('bank');
    Route::get('/bank/{bankAccount}', [BankController::class, 'show'])->name('bank.show');
    Route::get('/bank/{bankAccount}/upload', [BankController::class, 'upload'])->name('bank.upload');
    Route::post('/bank/{bankAccount}/upload', [BankController::class, 'uploadAction'])->name('bank.upload.action');
    Route::get('/bank/{bankAccount}/compare', [BankController::class, 'compare'])->name('bank.compare');
    Route::post('/bank/link', [BankController::class, 'link'])->name('bank.link');
    Route::post('/bank/create-and-link', [BankController::class, 'createAndLink'])->name('bank.create-and-link');

    Route::get('/bank-proposals', [BankProposalController::class, 'index'])->name('proposals');
    Route::post('/bank-proposals', [BankProposalController::class, 'store'])->name('proposals.store');
    Route::get('/bank-proposals/create', [BankProposalController::class, 'create'])->name('proposals.create');
    Route::delete('/bank-proposals/{bankProposal}', [BankProposalController::class, 'destroy'])->name('proposals.destroy');
});

require __DIR__.'/auth.php';
