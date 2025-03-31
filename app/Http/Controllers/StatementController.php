<?php

namespace App\Http\Controllers;

use App\Types\AccountType;
use Inertia\Inertia;

class StatementController extends Controller
{
    public function index()
    {
        return Inertia::render('Statement/Statement', [
            'statement' => AccountType::ROOT->statement(),
        ]);
    }
}
