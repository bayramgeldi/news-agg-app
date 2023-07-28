<?php

namespace App\Filament\Widgets;

use App\Models\Category;
use App\Models\MainCategory;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Card;
use Illuminate\Support\Facades\Cache;
class StatsOverview extends BaseWidget
{
    protected function getCards(): array
    {

        return [
            Card::make('Main categories', MainCategory::count())->color('success'),
            Card::make('Categories', Category::count()),
            Card::make('Users', User::count()),
        ];
    }
}
