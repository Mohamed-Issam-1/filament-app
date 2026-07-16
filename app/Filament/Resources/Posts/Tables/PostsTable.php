<?php

namespace App\Filament\Resources\Posts\Tables;

use App\Models\Post;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ReplicateAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\DatePicker;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\ColorColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class PostsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->toggleable(isToggledHiddenByDefault: true),

                ImageColumn::make('image')
                    ->disk('public')
                    ->toggleable(),

                TextColumn::make('title')
                    ->sortable()
                    ->searchable()
                    ->toggleable(),

                TextColumn::make('slug')
                    ->sortable()
                    ->searchable()
                    ->toggleable(),

                TextColumn::make('category.name')
                    ->label('Category')
                    ->sortable()
                    ->searchable()
                    ->toggleable(),

                ColorColumn::make('color')
                    ->toggleable(),

                TextColumn::make('created_at')
                    ->label('Created At')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('tags')
                    ->label('Tags')
                    ->badge()
                    ->toggleable(isToggledHiddenByDefault: true),

                IconColumn::make('published')
                    ->boolean()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('title', 'asc')
            ->filters([
                Filter::make('created_at')
                    ->label('Creation Date')
                    ->schema([
                        DatePicker::make('created_at')
                            ->label('Select Date'),
                    ])
                    ->query(
                        function (Builder $query, array $data): Builder {
                            return $query->when(
                                $data['created_at'] ?? null,
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', $date),
                            );
                        }
                    ),

                SelectFilter::make('category')
                    ->label('Category')
                    ->relationship('category', 'name')
                    ->searchable()
                    ->preload()
                    ->placeholder('Select a category'),
            ])
            ->recordActions([
                Action::make('status')
                    ->label('Change Status')
                    ->icon(Heroicon::PencilSquare)
                    ->schema([
                        Checkbox::make('published')
                            ->label('Published'),
                    ])
                    ->fillForm(
                        fn (Post $record): array => [
                            'published' => $record->published,
                        ]
                    )
                    ->action(function (array $data, Post $record): void {
                        $record->update([
                            'published' => $data['published'],
                        ]);
                    }),

                ViewAction::make(),
                ReplicateAction::make(),
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
