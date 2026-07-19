<?php

namespace App\Filament\Widgets;

use App\Models\Post;
use App\Models\Product;
use App\Models\User;
use Filament\Support\Enums\IconPosition;
use Filament\Support\Icons\Heroicon;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Database\Eloquent\Builder;

class TestStatsWidget extends StatsOverviewWidget
{
    use InteractsWithPageFilters;

    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $startDate = $this->pageFilters['startDate'] ?? null;
        $endDate = $this->pageFilters['endDate'] ?? null;

        return [
            Stat::make('Total Users', User::query()
                ->when($startDate, fn (Builder $query) => $query->whereDate('created_at', '>=', $startDate))
                ->when($endDate, fn (Builder $query) => $query->whereDate('created_at', '<=', $endDate))
                ->count(), )
                ->description('Total number of users of the year')
                ->descriptionIcon(
                    Heroicon::ArrowUpLeft,
                    IconPosition::Before
                )
                ->chart(
                    User::query()
                        ->selectRaw(
                            'MONTH(created_at) as month, COUNT(*) as count'
                        )
                        ->whereYear('created_at', now()->year)
                        ->groupBy('month')
                        ->orderBy('month')
                        ->pluck('count')
                        ->prepend(0)
                        ->toArray()
                )
                ->descriptionColor('success')
                ->color('success'),

            Stat::make('Total Posts', Post::query()->count())
                ->description('Total number of posts of the year')
                ->descriptionIcon(
                    Heroicon::ArrowUpLeft,
                    IconPosition::Before
                )
                ->chart(
                    Post::query()
                        ->selectRaw(
                            'MONTH(created_at) as month, COUNT(*) as count'
                        )
                        ->whereYear('created_at', now()->year)
                        ->groupBy('month')
                        ->orderBy('month')
                        ->pluck('count')
                        ->prepend(0)
                        ->toArray()
                )
                ->descriptionColor('warning')
                ->color('warning'),

            Stat::make('Total Products', Product::query()->count())
                ->description('Total number of products of the year')
                ->descriptionIcon(
                    Heroicon::ArrowUpLeft,
                    IconPosition::Before
                )
                ->chart(
                    Product::query()
                        ->selectRaw(
                            'MONTH(created_at) as month, COUNT(*) as count'
                        )
                        ->whereYear('created_at', now()->year)
                        ->groupBy('month')
                        ->orderBy('month')
                        ->pluck('count')
                        ->prepend(0)
                        ->toArray()
                )
                ->descriptionColor('info')
                ->color('info'),
        ];
    }
}
