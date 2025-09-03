<?php

declare(strict_types=1);

namespace App\Filament\Traits;

trait WithoutLegend
{
    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => [
                    'display' => false,
                ],
            ],
        ];
    }
}
