<?php

namespace App\View\Components;

use App\Dto\StatementDto;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class SubStatement extends Component
{
    public function __construct(public StatementDto $statement) {}

    public function render(): View|Closure|string
    {
        return view('components.sub-statement');
    }
}
