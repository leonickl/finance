<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Dto\StatementDto;
use Livewire\Component;

final class SubStatement extends Component
{
    public StatementDto $statement;

    public function render()
    {
        return view('livewire.sub-statement');
    }
}
