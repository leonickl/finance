<?php

namespace App\Http\Controllers;

use App\Bank\UploadHandler;
use App\Models\Account;
use App\Models\BankAccount;
use App\Models\BankTransaction;
use App\Models\Transaction;

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
        if (request()->hasFile('file')) {
            request()->validate([
                'file' => 'file|mimes:txt,csv|max:10240', // 10MB max, plain text or CSV only
            ]);

            $file = request()->file('file');
            $content = file_get_contents($file->getRealPath());
        } else {
            request()->validate([
                'value' => 'required|string',
            ]);

            $content = request()->input('value');
        }

        (new UploadHandler($bankAccount))->uploadText($content);

        return redirect()->route('bank.compare', $bankAccount->id);
    }

    public function compare(BankAccount $bankAccount)
    {
        return inertia('Bank/Compare', [
            'bankAccount' => $bankAccount,
            'bankTransactions' => $bankAccount
                ->bankTransactions()
                ->whereNull('transaction_id')
                ->where('skipped', 0)
                ->get(),
            'accounts' => Account::all(),
        ]);
    }

    public function link()
    {
        $valid = request()->validate([
            'bankTransactionId' => 'required|numeric',
            'transactionId' => 'required|numeric',
        ]);

        $bankTransaction = BankTransaction::findOrFail($valid['bankTransactionId']);
        $transaction = Transaction::findOrFail($valid['transactionId']);

        $bankTransaction->transaction_id = $transaction->id;

        $bankTransaction->save();

        return response()->json($bankTransaction);
    }
}
