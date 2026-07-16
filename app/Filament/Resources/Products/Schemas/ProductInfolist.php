<?php

namespace App\Filament\Resources\Products\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;
use Filament\Support\Enums\FontWeight;
use Filament\Support\Icons\Heroicon;

class ProductInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Tabs::make('Tabs')
                    ->tabs([
                        Tab::make('Product Info')
                            ->icon(Heroicon::AcademicCap)
                            ->schema([
                                TextEntry::make('id')
                                    ->label('Product ID')
                                    ->weight(FontWeight::Bold)
                                    ->color('primary'),

                                TextEntry::make('name')
                                    ->label('Product Name')
                                    ->weight(FontWeight::Bold)
                                    ->color('primary'),

                                TextEntry::make('sku')
                                    ->label('Product SKU')
                                    ->weight(FontWeight::Bold)
                                    ->badge()
                                    ->color('success'),

                                TextEntry::make('description')
                                    ->label('Product Description')
                                    ->weight(FontWeight::Bold)
                                    ->color('primary'),

                                TextEntry::make('created_at')
                                    ->label('Product Created Date')
                                    ->weight(FontWeight::Bold)
                                    ->color('info')
                                    ->date('m/d/Y'),
                            ]),

                        Tab::make('Pricing & Stock')
                            ->icon(Heroicon::CurrencyDollar)
                            ->badge(10)
                            ->badgeColor('info')
                            ->schema([
                                TextEntry::make('price')
                                    ->label('Product Price')
                                    ->weight(FontWeight::Bold)
                                    ->icon(Heroicon::CurrencyDollar)
                                    ->color('primary'),

                                TextEntry::make('stock')
                                    ->label('Product Stock')
                                    ->weight(FontWeight::Bold)
                                    ->color('primary'),
                            ]),

                        Tab::make('Media & Status')
                            ->icon(Heroicon::Photo)
                            ->schema([
                                ImageEntry::make('image')
                                    ->label('Product Image')
                                    ->disk('public'),

                                IconEntry::make('is_active')
                                    ->label('Is Active?')
                                    ->boolean(),

                                IconEntry::make('is_featured')
                                    ->label('Is Featured?')
                                    ->boolean(),
                            ]),
                    ])
                    ->columnSpanFull()
                    ->vertical(),
            ]);
    }
}
