<?php

namespace App\Filament\Resources\MainCategoryResource\RelationManagers;

use App\Models\Category;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Table;
use Filament\Tables\Columns\TextColumn;

class Sub_categoriesRelationManager extends RelationManager
{
    protected static string $relationship = 'sub_categories';

    protected static ?string $recordTitleAttribute = 'title';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('source')
                    ->required(),

                TextInput::make('name')
                    ->required(),

                TextInput::make('title')
                    ->required(),

                TextInput::make('description')
                    ->required(),

                Placeholder::make('created_at')
                    ->label('Created Date')
                    ->content(fn(?Category $record): string => $record?->created_at?->diffForHumans() ?? '-'),

                Placeholder::make('updated_at')
                    ->label('Last Modified Date')
                    ->content(fn(?Category $record): string => $record?->updated_at?->diffForHumans() ?? '-'),

                Select::make('main_category')
                    ->relationship('main_categories', 'name')
                    ->searchable()
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('main_categories.title')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('source'),

                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('title')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('description'),

            ]);
    }

    public static function getOptions(): array
    {
        return Category::query()
            ->orderBy('name')
            ->get()
            ->mapWithKeys(fn (Category $category) => [$category->id => $category->name])
            ->toArray();
    }
}
