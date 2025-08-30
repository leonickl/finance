<?php

namespace App\Filament\Widgets;

use App\Statistics\Budget\MonthlyBudget;
use App\Types\Date\Month;
use Filament\Widgets\ChartWidget;

class MonthlyIncomeChart extends ChartWidget
{
    protected ?string $heading = 'Monthly Income';

protected function getData(): array
{
    $budget = new MonthlyBudget(Month::now()->plus(session('lag')));
    $donutData = $budget->incomeDonutData();

    return [
        'datasets' => $donutData['datasets'],
        'labels' => $donutData['labels'],
        'options' => [
            'plugins' => [
                'datalabels' => [
                    'formatter' => 'function(value, context) { return value; }',
                    'color' => '#22c55e',
                    'font' => [
                        'weight' => 'bold',
                        'size' => 24,
                    ],
                    'display' => 'auto',
                ],
            ],
        ],
    ];
}



    protected function getType(): string
    {
        return 'doughnut';
    }
}
