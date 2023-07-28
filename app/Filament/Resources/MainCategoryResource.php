<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MainCategoryResource\Pages;
use App\Filament\Resources\MainCategoryResource\RelationManagers\Sub_categoriesRelationManager;
use App\Models\Category;
use App\Models\MainCategory;
use Filament\Forms\Components\MultiSelect;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables\Actions\AssociateAction;
use Filament\Tables\Actions\DissociateBulkAction;
use Filament\Tables\Columns\TagsColumn;
use Filament\Tables\Columns\TextColumn;

class MainCategoryResource extends Resource
{
    protected static ?string $model = MainCategory::class;

    protected static ?string $slug = 'main-categories';

    protected static ?string $recordTitleAttribute = 'Title';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->required()->disabled(),

                TextInput::make('title')
                    ->required(),

                Textarea::make('description')
                    ->required(),
                Placeholder::make('created_at')
                    ->label('Created Date')
                    ->content(fn(?MainCategory $record): string => $record?->created_at?->diffForHumans() ?? '-'),

                Placeholder::make('updated_at')
                    ->label('Last Modified Date')
                    ->content(fn(?MainCategory $record): string => $record?->updated_at?->diffForHumans() ?? '-'),

                Select::make('sub_categories')->relationship('sub_categories', 'title')
                    ->multiple()->searchable(),
            ]);
    }

    public static function canCreate(): bool
    {
        return false;
    }


    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('title')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('description'),
                TextColumn::make('sub_categories_count')->counts('sub_categories')->label('Sub Categories'),
                TagsColumn::make('sub_categories.name')->label('Sub Categories'),
            ])->bulkActions([
            ])->headerActions([
            ]);
    }

    public static function getRelations(): array
    {
        return [
            Sub_categoriesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMainCategories::route('/'),
            'edit' => Pages\EditMainCategory::route('/{record}/edit'),
        ];
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['name', 'title'];
    }
}
