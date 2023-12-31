<?php

namespace App\Filament\Resources\SubCategoryResource\Pages;

use App\Filament\Resources\SubCategoryResource;
use Filament\Pages\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListSubCategories extends ListRecords
{
    protected static string $resource = SubCategoryResource::class;

    protected function getActions(): array
    {
        return [
        ];
    }
}
