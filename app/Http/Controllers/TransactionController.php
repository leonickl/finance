<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Inertia\Inertia;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Inertia::render('Transactions/List', [
            'transactions' => (request('search') ? Transaction::search(request('search')) : Transaction::query())
                ->orderByDesc('timestamp')
                ->paginate(20)
                ->appends(request()->input()),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $transaction = new Transaction;

        $transaction->debit_id = $request->integer('debit_id');
        $transaction->credit_id = $request->integer('credit_id');
        $transaction->value = $request->float('value');
        $transaction->text = $request->get('text');
        $transaction->claim_id = $request->integer('claim_id') ?: null;
        $transaction->group_uid = $request->get('group_uid');
        $transaction->person_id = $request->integer('person_id') ?: null;

        return $transaction;
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return Inertia::render('Transactions/Show', [
            'transaction' => Transaction::findOrFail($id),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
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

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Transaction::destroy($id);
    }
}
