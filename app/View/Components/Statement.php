<?php

declare(strict_types=1);

namespace App\View\Components;

use App\Types\AccountType;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

final class Statement extends Component
{
    public function render(): View | Closure | string
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
