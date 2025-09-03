<?php

declare(strict_types=1);

namespace App\Filament\Widgets;

use App\Types\AccountType;
use Filament\Widgets\Widget;

final class Statement extends Widget
{
    protected string $view = 'filament.widgets.statement';

    public function statement()
    {
        return AccountType::ROOT->statement();
    }
}
