<?php

namespace App\Filament\Resources\MainCategoryResource\Pages;

use App\Filament\Resources\MainCategoryResource;
use Filament\Pages\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditMainCategory extends EditRecord
{
    protected static string $resource = MainCategoryResource::class;


    protected function getActions(): array
    {
        return [
        ];
    }
}
