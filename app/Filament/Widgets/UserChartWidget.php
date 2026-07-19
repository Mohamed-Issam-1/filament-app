<?php

namespace App\Filament\Widgets;

use App\Models\User;
use Filament\Widgets\ChartWidget;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;

class UserChartWidget extends ChartWidget
{
    use InteractsWithPageFilters;

    protected ?string $heading = 'New Registered User Chart';

    protected string $color = 'info';

    protected static ?int $sort = 2;

    protected function getData(): array
    {
        $startDate = $this->pageFilters['startDate'] ?? null;
        $endDate = $this->pageFilters['endDate'] ?? null;

        $start = $startDate
            ? now()->parse($startDate)->startOfYear()
            : now()->startOfYear();

        $end = $endDate
            ? now()->parse($endDate)->endOfDay()
            : now()->endOfMonth();

        $data = Trend::model(User::class)
            ->between(
                start: $start,
                end: $end,
            )
            ->perMonth()
            ->count();

        return [
            'datasets' => [
                [
                    'label' => 'Users Created',
                    'data' => $data->map(
                        fn (TrendValue $value) => $value->aggregate
                    ),
                ],
            ],
            'labels' => $data->map(
                fn (TrendValue $value) => $value->date
            ),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
