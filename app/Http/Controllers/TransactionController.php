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

    public function show(int $id)
    {
        $transaction = Transaction::findOrFail($id);

        return Inertia::render('Transactions/Show', [
            'transaction' => $transaction,
            'repayments' => $transaction->repayments,
            'claims' => Transaction::allClaims(),
        ]);
    }

    public function create(Request $request)
    {
        return Inertia::render('Transactions/Create', [
            'accounts' => Account::all(),
            'claims' => Transaction::allClaims(),
        ]);
    }

    public function update(Request $request, string $id)
    {
        $transaction = Transaction::findOrFail($id);

        if ($request->exists('debitId')) {
            $transaction->debit_id = $request->integer('debitId');
        }
        if ($request->exists('creditId')) {
            $transaction->credit_id = $request->integer('creditId');
        }
        if ($request->exists('value')) {
            $transaction->value = $request->float('value');
        }
        if ($request->exists('text')) {
            $transaction->text = $request->get('text');
        }
        if ($request->exists('claimId')) {
            $transaction->claim_id = $request->integer('claimId') ?: null;
        }
        if ($request->exists('groupUid')) {
            $transaction->group_uid = $request->get('groupUid');
        }
        if ($request->exists('personId')) {
            $transaction->person_id = $request->integer('personId') ?: null;
        }

        $transaction->save();

        return back();
    }

    public function destroy(string $id)
    {
        Transaction::destroy($id);
    }
}
