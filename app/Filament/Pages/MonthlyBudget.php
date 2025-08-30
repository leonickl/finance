<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\MonthlyExpensesChart;
use App\Filament\Widgets\MonthlyIncomeChart;
use Filament\Actions\Action;
use Filament\Pages\Page;

class MonthlyBudget extends Page
{
    protected string $view = 'filament.pages.monthly-budget';

    protected function getHeaderActions(): array
    {
        $lag = request()->integer('lag');

        session(['lag' => $lag]);

        return [
            Action::make('back')
                ->url(route('filament.finance.pages.monthly-budget', ['lag' => $lag - 1])),
            Action::make('forward')
                ->url(route('filament.finance.pages.monthly-budget', ['lag' => $lag + 1])),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            MonthlyIncomeChart::class,
            MonthlyExpensesChart::class,
        ];
    }
}
