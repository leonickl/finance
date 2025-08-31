<?php

namespace App\Livewire;

use App\Dto\StatementDto;
use Livewire\Component;

class SubStatement extends Component
{
    public StatementDto $statement;

    public function render()
    {
        return view('livewire.sub-statement');
    }
}
