<?php

namespace App\Services;

use App\Models\Author;
use App\Models\Category;
use Illuminate\Support\Facades\Http;

class NewsSource
{
    const THE_GUARDIAN = 'TheGuardian';
    const NEWS_API_ORG = 'NewsAPIOrg';
    const NEW_YORK_TIMES = 'NewYorkTimes';


    protected array $authors = [];

    public static function all(): array
    {
        return [
            [
                'name' => self::THE_GUARDIAN,
                'title' => 'The Guardian',
                'description' => 'The Guardian',
                'categories' => Category::where('source', self::THE_GUARDIAN)->get(),
                'authors' => Author::where('source', self::THE_GUARDIAN)->get(),
            ],
            [
                'name' => self::NEWS_API_ORG,
                'title' => 'News API Org',
                'description' => 'News API Org',
                'categories' => Category::where('source', self::NEWS_API_ORG)->get(),
                'authors' => Author::where('source', self::NEWS_API_ORG)->get(),
            ],
            [
                'name' => self::NEW_YORK_TIMES,
                'title' => 'New York Times',
                'description' => 'New York Times',
                'categories' => Category::where('source', self::NEW_YORK_TIMES)->get(),
                'authors' => Author::where('source', self::NEW_YORK_TIMES)->get(),
            ],
        ];
    }


}
