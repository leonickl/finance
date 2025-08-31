<?php

namespace App\View\Components;

use App\Types\AccountType;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Statement extends Component
{
    public function render(): View|Closure|string
    {
        return view('components.statement');
    }

    public function assets()
    {
        return AccountType::ASSETS->statement();
    }

    public function liabilities()
    {
        return AccountType::LIABILITIES->statement();
    }
}
