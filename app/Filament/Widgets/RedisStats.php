<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Card;
use Illuminate\Support\Facades\Cache;

class RedisStats extends BaseWidget
{
    protected function getCards(): array
    {

        // Retrieve cache statistics
        $redisStats = Cache::store('redis')->connection()->info();
        return [
            Card::make('Redis/ News cached', $redisStats['used_memory_human']),
            Card::make('Redis/ News cached RSS', $redisStats['used_memory_rss_human']),
            Card::make('Redis/ Total reads', $redisStats['total_reads_processed']),
            Card::make('Redis/ Total writes', $redisStats['total_writes_processed']),
        ];
    }
}
