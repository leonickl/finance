<?php

namespace App\Filament\Widgets;

use App\Types\AccountType;
use Filament\Widgets\Widget;

class Statement extends Widget
{
    protected string $view = 'filament.widgets.statement';

    public function statement()
    {
        return AccountType::ROOT->statement();
    }
}
