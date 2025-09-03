<?php

declare(strict_types=1);

namespace App\View\Components;

use App\Dto\StatementDto;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

final class SubStatement extends Component
{
    public function __construct(public StatementDto $statement) {}

    public function render(): View | Closure | string
    {
        return view('components.sub-statement');
    }
}
