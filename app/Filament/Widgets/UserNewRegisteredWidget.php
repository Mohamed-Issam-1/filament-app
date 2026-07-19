<?php

namespace App\Filament\Widgets;

use App\Models\User;
use Filament\Actions\BulkActionGroup;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;

class UserNewRegisteredWidget extends TableWidget
{
    use InteractsWithPageFilters;

    protected static ?int $sort = 4;

    protected int|string|array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        $startDate = $this->pageFilters['startDate'] ?? null;
        $endDate = $this->pageFilters['endDate'] ?? null;

        return $table
            ->query(fn (): Builder => User::query()
                ->when($startDate, fn (Builder $query) => $query->whereDate('created_at', '>=', $startDate))
                ->when($endDate, fn (Builder $query) => $query->whereDate('created_at', '<=', $endDate))
                ->latest()
                ->take(10))
            ->columns([
                TextColumn::make('id'),
                TextColumn::make('name'),
                TextColumn::make('email'),
                TextColumn::make('created_at'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                //
            ])
            ->recordActions([
                //
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    //
                ]),
            ]);
    }
}
