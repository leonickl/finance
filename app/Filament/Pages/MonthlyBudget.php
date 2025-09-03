<?php

declare(strict_types=1);

namespace App\Filament\Pages;

use App\Filament\Widgets\MonthlyExpensesChart;
use App\Filament\Widgets\MonthlyIncomeChart;
use App\Filament\Widgets\MonthlyInfo;
use Carbon\Carbon;
use Filament\Actions\Action;
use Filament\Pages\Page;

final class MonthlyBudget extends Page
{
    protected string $view = 'filament.pages.monthly-budget';

    protected function getHeaderActions(): array
    {
        $lag = request()->integer('lag');

        session(['lag' => $lag]);

        return [
            Action::make('back')
                ->url(route('filament.finance.pages.monthly-budget', ['lag' => $lag - 1])),
            Action::make('now')
                ->url(route('filament.finance.pages.monthly-budget', ['lag' => 0])),
            Action::make('forward')
                ->url(route('filament.finance.pages.monthly-budget', ['lag' => $lag + 1])),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            MonthlyIncomeChart::class,
            MonthlyExpensesChart::class,
            MonthlyInfo::class,
        ];
    }

    public function getTitle(): string
    {
        $month = Carbon::now()
            ->addMonths(request()->integer('lag'))
            ->translatedFormat('F Y'); // e.g., "August 2025"

        return "Monthly Budget - {$month}";
    }

    public static function getNavigationIcon(): string
    {
        return 'heroicon-o-calendar';
    }
}
