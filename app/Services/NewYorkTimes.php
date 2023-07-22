<?php

namespace App\Services;

use App\Models\Author;
use App\Models\Category;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Str;

class NewYorkTimes extends NewsSource
{
    static array $categories = [
        "arts", "automobiles", "books/review", "business", "fashion", "food", "health", "home", "insider", "magazine",
        "movies", "nyregion", "obituaries", "opinion", "politics", "realestate", "science", "sports", "sundayreview",
        "technology", "theater", "t-magazine", "travel", "upshot", "us", "world"
    ];

    public static function getNewsByCategory(Category $category = null): array
    {
        if (!$category) {
            $category = Category::where('source', self::NEW_YORK_TIMES)->where('name', 'home')->first();
        }
        if (!$category) {
            return [];
        }
        $news = [];
        $response = Http::get(config('services.NewYorkTimes.base_url').'/topstories/v2/'.$category->name.'.json', [
            'api-key' => config('services.NewYorkTimes.key')
        ]);
        if ($response->failed()) {
            return $news;
        }
        $data = json_decode($response->getBody()->getContents(), true);;
        $news['totalResults'] = $data['num_results'];
        foreach ($data['results'] as $article) {
            $subSource = [
                'id' => NewsSource::NEW_YORK_TIMES,
                'name' => "New York Times",
            ];

            //insert author to db
            if (isset($article['byline'])){
                $author = Author::firstOrCreate([
                    'name' => Str::slug($article['byline']),
                    'source' => self::NEW_YORK_TIMES
                ],[
                    'name' => Str::slug($article['byline']),
                    'source' => self::NEW_YORK_TIMES,
                    'description' => $article['byline'],
                    'title' => $article['byline'],
                ]);
            }

            $articleClass = new Article(
                self::NEW_YORK_TIMES,
                (array) $subSource,
                (string) isset($article['byline']) ? $article['byline'] : self::NEW_YORK_TIMES,
                (string) $article['title'],
                $category,
                (string) isset($article['abstract']) ? $article['abstract'] : '',
                (string) $article['url'],
                (string) $article['multimedia'][1]['url'],
                (string) Carbon::parse($article['published_date'])->format('Y-m-d H:i:s'),
                (string) $article['abstract']
            );
            $news['data'][] = $articleClass->collect();
        }
        return $news;
    }

    public static function getCategories(): array
    {
        $categories = [];
        $response = Http::get(config('services.NewYorkTimes.base_url').'/news/v3/content/section-list.json', [
            'api-key' => config('services.NewYorkTimes.key')
        ]);
        if ($response->failed()) {
            return $categories;
        }
        $data = json_decode($response->getBody()->getContents(), true);

        foreach ($data['results'] as $category) {
            if (!in_array($category['section'], self::$categories)) {
                continue;
            }
            $cat = [
                'source' => NewsSource::NEW_YORK_TIMES,
                'name' => $category['section'],
                'title' => $category['display_name'],
                'description' => $category['display_name'],
            ];
            $categories[] = $cat;
            Category::firstOrCreate(
                ['name' => $cat['name'], 'source' => $cat['source']],
                [
                    'source' => $cat['source'],
                    'name' => $cat['name'],
                    'title' => $cat['title'],
                    'description' => $cat['description'],
                ]);
        }
        Category::firstOrCreate(
            ['name' => 'home', 'source' => self::NEW_YORK_TIMES],
            [
                'source' => self::NEW_YORK_TIMES,
                'name' => 'home',
                'title' => 'General',
                'description' => 'General',
            ]);
        return $categories;
    }

}
