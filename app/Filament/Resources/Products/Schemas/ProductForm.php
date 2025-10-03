<?php

namespace App\Filament\Resources\Products\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\{Select, TextInput, Textarea, Toggle, Repeater, FileUpload};
use Illuminate\Support\Str;

class ProductForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('category_id')
                ->label('Category')
                ->relationship('category', 'name')
                ->searchable()
                ->preload()
                ->required(),

            TextInput::make('name')
                ->required()
                ->live(onBlur: true)
                ->afterStateUpdated(fn ($state, $set) => $set('slug', Str::slug($state))),

            TextInput::make('slug')
                ->required()
                ->unique(ignoreRecord: true),

            Textarea::make('description')->rows(4),

            TextInput::make('price_cents')
                ->label('Price (¢)')
                ->numeric()
                ->minValue(0)
                ->required()
                ->helperText('Store price in cents (e.g. $12.34 → 1234).'),

            TextInput::make('stock')->numeric()->minValue(0)->required(),

            Toggle::make('is_active')->default(true),

            Repeater::make('images')
                ->relationship('images')
                ->schema([
                    FileUpload::make('path')
                        ->label('Image')
                        ->image()
                        ->disk('public')         // ✅ ensure files go to public disk
                        ->directory('products')  // e.g. storage/app/public/products
                        ->required(),
                    TextInput::make('position')->numeric()->default(0),
                ])
                ->columns(2),
        ])->columns(2);
    }
}
