<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Types\AccountType;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AccountController extends Controller
{
    public function index()
    {
        return Inertia::render('Accounts/List', [
            'accounts' => Account::latest()->get(),
        ]);
    }

    public function create()
    {
        return inertia('Accounts/Create', [
            'accountTypes' => AccountType::all(),
        ]);
    }

    public function store(Request $request)
    {
        Account::create([
            'name' => $request->get('name'),
            'archived' => $request->boolean('archived'),
            'type' => AccountType::make($request->integer('type')),
            'recurring' => $request->boolean('recurring'),
            'interest_rate' => $request->float('interest_rate'),
        ]);

        return redirect()->route('accounts');
    }

    public function show(Account $account)
    {
        return Inertia::render('Accounts/Show', [
            'account' => $account,
            'balance' => $account->balance()->toArray(),
            'transactions' => $account->transactions(desc: true)->toArray(),
        ]);
    }

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

    public function destroy(string $id)
    {
        Account::destroy($id);
    }
}
