<?php

namespace App\Filament\Resources\MainCategoryResource\Pages;

use App\Filament\Resources\MainCategoryResource;
use Filament\Pages\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListMainCategories extends ListRecords
{
    protected static string $resource = MainCategoryResource::class;

    protected function getActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
