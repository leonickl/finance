<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\YearlyExpensesChart;
use App\Filament\Widgets\YearlyExpensesInfo;
use App\Filament\Widgets\YearlyIncomeChart;
use App\Filament\Widgets\YearlyIncomeInfo;
use Filament\Actions\Action;
use Filament\Pages\Page;
use Illuminate\Support\Carbon;

class YearlyBudget extends Page
{
    protected string $view = 'filament.pages.yearly-budget';

    protected function getHeaderActions(): array
    {
        $lag = request()->integer('lag');

        session(['lag' => $lag]);

        return [
            Action::make('back')
                ->url(route('filament.finance.pages.yearly-budget', ['lag' => $lag - 1])),
            Action::make('now')
                ->url(route('filament.finance.pages.yearly-budget', ['lag' => 0])),
            Action::make('forward')
                ->url(route('filament.finance.pages.yearly-budget', ['lag' => $lag + 1])),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            YearlyIncomeChart::class,
            YearlyExpensesChart::class,
            YearlyIncomeInfo::class,
            YearlyExpensesInfo::class,
        ];
    }

    public function getTitle(): string
    {
        $year = Carbon::now()
            ->addMonths(request()->integer('lag'))
            ->translatedFormat('Y');

        return "Yearly Budget - $year";
    }

    public static function getNavigationIcon(): string
    {
        return 'heroicon-o-calendar';
    }
}
