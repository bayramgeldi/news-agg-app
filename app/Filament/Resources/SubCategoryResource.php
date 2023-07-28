<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SubCategoryResource\Pages;
use App\Models\Category;
use App\Services\NewsSource;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class SubCategoryResource extends Resource
{
    protected static ?string $model = Category::class;

    protected static ?string $slug = 'sub-categories';

    protected static ?string $recordTitleAttribute = 'title';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('source')
                    ->required()->disabled(),

                TextInput::make('name')
                    ->required()->disabled(),

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

            ])->filters([
                SelectFilter::make('main_categories')->label('Main Category')
                    ->relationship('main_categories', 'title'),
                SelectFilter::make('source')->label('Source')->options(function (){
                    $options = [];
                    foreach (NewsSource::all(true) as $key => $value) {
                        $options[$value] = $value;
                    }
                    return $options;
                })
            ])
            ->bulkActions([
                // ...
            ])->headerActions([
                // ...
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSubCategories::route('/'),
            'edit' => Pages\EditSubCategory::route('/{record}/edit'),
        ];
    }

    public static function canCreate(): bool
    {
        return false;
    }

    protected static function getGlobalSearchEloquentQuery(): Builder
    {
        return parent::getGlobalSearchEloquentQuery()->with(['main_categories']);
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['name', 'title', 'main_categories.name'];
    }

    public static function getGlobalSearchResultDetails(Model $record): array
    {
        $details = [];

        if ($record->main_categories) {
            $record->main_categories->each(function ($main_category) use (&$details) {
                $details['Main_category'] = $main_category->name;
            });
            //$details['Main_category'] = $record->main_category->name;
        }

        return $details;
    }
}
