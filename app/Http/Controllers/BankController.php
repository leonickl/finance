<?php

namespace App\Http\Controllers;

use App\Models\BankAccount;
use Illuminate\Http\Request;

class BankController extends Controller
{
    public function index()
    {
        return inertia('Bank/List', [
            'bankAccounts' => BankAccount::all(),
        ]);
    }

    public function show(BankAccount $bankAccount)
    {
        return inertia('Bank/Show', [
            'bankAccount' => $bankAccount,
        ]);
    }

    public function upload(BankAccount $bankAccount)
    {
        return inertia('Bank/Upload', [
            'bankAccount' => $bankAccount,
        ]);
    }

    public function uploadAction(BankAccount $bankAccount)
    {
        return redirect()->route('bank.compare', $bankAccount->id);
    }

    public function compare(BankAccount $bankAccount)
    {
        return inertia('Bank/Show', [
            'bankAccount' => $bankAccount,
        ]);
    }
}
