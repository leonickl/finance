<?php

declare(strict_types=1);

namespace App\Filament\Widgets;

use App\Statistics\Charts\LineChartInOutTotal;
use App\Types\Date\Month;
use App\Types\Date\MonthRange;
use Filament\Forms\Components\DatePicker;
use Filament\Schemas\Schema;
use Filament\Widgets\ChartWidget;
use Filament\Widgets\ChartWidget\Concerns\HasFiltersSchema;

final class RegressionChart extends ChartWidget
{
    use HasFiltersSchema;

    protected ?string $heading = 'Regression';

    protected function getData(): array
    {
        $range = new MonthRange(
            start: Month::tryFromString(@$this->filters['start']) ?? Month::now()->minus(12 * 3),
            end: Month::tryFromString(@$this->filters['end']) ?? Month::now(),
        );

        return LineChartInOutTotal::make($range, horizon: 5)->chartData();
    }

    protected function getType(): string
    {
        return 'line';
    }

    public function filtersSchema(Schema $schema): Schema
    {
        return $schema->components([
            DatePicker::make('start')
                ->default(now()->subYears(3)),
            DatePicker::make('end')
                ->default(now()),
        ]);
    }
}
