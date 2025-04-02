<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Transaction;
use App\Types\Currency;
use App\Types\Date\Date;
use Illuminate\Http\Request;
use Inertia\Inertia;

class TransactionController extends Controller
{
    public function index()
    {
        return Inertia::render('Transactions/List', [
            'transactions' => (request('search') ? Transaction::search(request('search')) : Transaction::query())
                ->orderByDesc('timestamp')
                ->paginate(20)
                ->appends(request()->input()),
        ]);
    }

    public function store(Request $request)
    {
        $transaction = new Transaction;

        $transaction->debit_id = $request->integer('debitId');
        $transaction->credit_id = $request->integer('creditId');
        $transaction->date = Date::of($request->date('date', 'Y-m-d'));
        $transaction->value = $request->float('value');
        $transaction->currency = Currency::new($request->get('currency'));
        $transaction->text = $request->get('text');
        $transaction->claim_id = $request->integer('claimId') ?: null;
        $transaction->group_uid = $request->get('groupUid');
        $transaction->person_id = $request->integer('personId') ?: null;

        $transaction->save();

        return back();
    }

    public function show(string $id)
    {
        return Inertia::render('Transactions/Show', [
            'transaction' => Transaction::findOrFail($id),
        ]);
    }

    public function create(Request $request)
    {
        return Inertia::render('Transactions/Create', [
            'accounts' => Account::all(),
        ]);
    }

    public function update(Request $request, string $id)
    {
        $transaction = Transaction::findOrFail($id);

        $transaction->debit_id = $request->integer('debit_id');
        $transaction->credit_id = $request->integer('credit_id');
        $transaction->value = $request->float('value');
        $transaction->text = $request->get('text');
        $transaction->claim_id = $request->integer('claim_id') ?: null;
        $transaction->group_uid = $request->get('group_uid');
        $transaction->person_id = $request->integer('person_id') ?: null;

        return $transaction;
    }

    public function destroy(string $id)
    {
        Transaction::destroy($id);
    }
}
