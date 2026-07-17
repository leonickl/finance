<?php

declare(strict_types=1);

namespace App\Filament\Widgets;

use App\Statistics\Charts\ExpenseBudgetLines;
use App\Statistics\Charts\Lines;
use Filament\Schemas\Schema;
use Filament\Widgets\ChartWidget;
use Filament\Widgets\ChartWidget\Concerns\HasFiltersSchema;
use Filament\Forms\Components\Toggle;

final class ExpensesBudgetLinesChart extends ChartWidget
{
    use HasFiltersSchema;

    protected ?string $heading = 'Expenses Budget Lines';

    protected ?string $maxHeight = '500px';

    protected ?array $options = [
        'aspectRatio' => 2,
    ];

    protected function getData(): array
    {
        $cumulative = $this->filters['cumulative'] ?? false;

        return (new ExpenseBudgetLines(Lines::range(), $cumulative))->chartData();
    }

    protected function getType(): string
    {
        return 'line';
    }

    public function filtersSchema(Schema $schema): Schema
    {
        return $schema->components([
            Toggle::make('cumulative')
                ->label('Stacked')
                ->default(false),
        ]);
    }
}
