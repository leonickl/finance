<?php

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
