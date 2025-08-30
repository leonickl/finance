<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\MonthlyInfo;
use App\Filament\Widgets\YearlyInfo;
use Filament\Pages\Dashboard as BaseDashboard;
use Filament\Widgets\AccountWidget;

class Dashboard extends BaseDashboard
{
    public function getWidgets(): array
    {
        return [
            AccountWidget::class,
            MonthlyInfo::class,
            YearlyInfo::class,
        ];
    }
}
