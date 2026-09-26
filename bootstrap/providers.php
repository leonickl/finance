<?php

declare(strict_types=1);
use App\Providers\AppServiceProvider;
use App\Providers\Filament\FinancePanelProvider;
use App\Providers\MacroProvider;
use App\Providers\VoltServiceProvider;

return [
    AppServiceProvider::class,
    FinancePanelProvider::class,
    VoltServiceProvider::class,
    MacroProvider::class,
];
