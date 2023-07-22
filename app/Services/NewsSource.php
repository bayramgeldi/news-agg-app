<?php

namespace App\Services;

use App\Models\Category;
use Illuminate\Support\Facades\Http;

class NewsSource
{
    const THE_GUARDIAN = 'TheGuardian';
    const NEWS_API_ORG = 'NewsAPIOrg';
    const NEW_YORK_TIMES = 'NewYorkTimes';

    static array $categories = [
        "business",
        "entertainment", "general", "health", "science", "sports", "technology"
    ];
    protected array $authors = [];

    public static function all(): array
    {
        return [
            [
                'name' => self::THE_GUARDIAN,
                'title' => 'The Guardian',
                'description' => 'The Guardian',
                'categories' => Category::where('source', self::THE_GUARDIAN)->get(),
            ],
            [
                'name' => self::NEWS_API_ORG,
                'title' => 'News API Org',
                'description' => 'News API Org',
                'categories' => Category::where('source', self::NEWS_API_ORG)->get(),
            ],
            [
                'name' => self::NEW_YORK_TIMES,
                'title' => 'New York Times',
                'description' => 'New York Times',
                'categories' => Category::where('source', self::NEW_YORK_TIMES)->get(),
            ],
        ];
    }


    /**
     * @param  array  $categories
     */
    public function setCategories(array $categories): void
    {
        $this->categories = $categories;
    }

}
