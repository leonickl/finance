<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Types\AccountType;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AccountController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Inertia::render('Accounts/List', [
            'accounts' => Account::all(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $account = new Account;

        $account->name = $request->get('name');
        $account->archived = $request->boolean('archived');
        $account->type = AccountType::make($request->integer('type'));
        ($request->integer('group_id'));
        $account->recurring = $request->boolean('recurring');
        $account->interest_rate = $request->float('interest_rate');

        $account->save();

        return $account;
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        $account = Account::findOrFail($id);

        return Inertia::render('Accounts/Show', [
            'account' => $account,
            'balance' => $account->balance()->toArray(),
            'transactions' => $account->transactions(desc: true)->toArray(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $account = Account::findOrFail($id);

        $account->name = $request->get('name');
        $account->archived = $request->boolean('archived');
        $account->group_id = $request->integer('group_id');
        $account->recurring = $request->boolean('recurring');
        $account->interest_rate = $request->float('interest_rate');

        return $account;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Account::destroy($id);
    }
}
