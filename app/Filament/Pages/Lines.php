<?php

declare(strict_types=1);

namespace App\Filament\Pages;

use App\Filament\Widgets\ExpensesBudgetLinesChart;
use App\Filament\Widgets\IncomeBudgetLinesChart;
use App\Filament\Widgets\RegressionChart;
use Filament\Pages\Page;

final class Lines extends Page
{
    protected string $view = 'filament.pages.lines';

    public function getHeaderWidgets(): array
    {
        return [
            RegressionChart::class,
            ExpensesBudgetLinesChart::class,
            IncomeBudgetLinesChart::class,
        ];
    }

    public function getHeaderWidgetsColumns(): int
    {
        return 1;
    }

    public static function getNavigationIcon(): string
    {
        return 'heroicon-o-presentation-chart-line';
    }
}
