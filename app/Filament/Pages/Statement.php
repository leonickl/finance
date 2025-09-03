<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;

class Statement extends Page
{
    protected string $view = 'filament.pages.statement';

    protected function getHeaderWidgets(): array
    {
        return [
            \App\Filament\Widgets\Statement::class,
        ];
    }

    public function getHeaderWidgetsColumns(): int
    {
        return 1;
    }

    public static function getNavigationIcon(): string
    {
        return 'heroicon-o-calculator';
    }
}
