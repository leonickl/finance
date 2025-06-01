<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\BankProposal;
use Illuminate\Http\Request;

class BankProposalController extends Controller
{
    public function index()
    {
        return inertia('BankProposals/List', ['proposals' => BankProposal::latest()->get()]);
    }

    public function create()
    {
        return inertia('BankProposals/Create', ['accounts' => Account::all()]);
    }

    public function store(Request $request)
    {
        BankProposal::create($request->all());

        return redirect()->route('proposals');
    }

    public function destroy(BankProposal $bankProposal)
    {
        $bankProposal->delete();
    }
}
