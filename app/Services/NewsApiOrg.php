<?php

namespace App\Services;

use App\Models\Author;
use App\Models\Category;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use Str;

class NewsApiOrg extends NewsSource
{

    static array $categories = [
        "business",
        "entertainment", "general", "health", "science", "sports", "technology"
    ];

    public static function getNewsByCategory(Category $category = null): array
    {
        $news = [];
        $query = [
            'apiKey' => config('services.NewsAPIOrg.key'),
            'country' => 'us'
        ];
        if ($category) {
            $query['category'] = $category->name;
        }
        $response = Http::get(config('services.NewsAPIOrg.base_url').'/top-headlines', $query);

        if ($response->failed()) {
            return $news;
        }
        $data = json_decode($response->getBody()->getContents(), true);
        foreach ($data['articles'] as $article) {
            $subSource = $article['source'];
            $category = Category::where('source', self::NEWS_API_ORG)->where('name', 'general')->first();

            //save author to db
            if (isset($article['author'])) {
                $author = Author::firstOrCreate(
                    ['name' => Str::slug($article['author']), 'source' => self::NEWS_API_ORG],
                    [
                        'source' => self::NEWS_API_ORG,
                        'name' => Str::slug($article['author']),
                        'title' => $article['author'],
                        'description' => $article['author'],
                    ]);
            }

            $articleClass = new Article(
                self::NEWS_API_ORG,
                (array) $subSource,
                (string) $article['author'],
                (string) $article['title'],
                $category,
                (string) $article['description'],
                (string) $article['url'],
                (string) $article['urlToImage'],
                (string) Carbon::parse($article['publishedAt'])->format('Y-m-d H:i:s'),
                (string) $article['content']
            );
            $news[] = $articleClass->collect();
        }
        return $news;
    }

    public static function getCategories(): array
    {
        $categories = [];
        foreach (self::$categories as $category) {
            $categories[] = [
                'source' => NewsSource::NEWS_API_ORG,
                'name' => $category,
                'title' => $category,
                'description' => $category,
            ];
            Category::updateOrInsert(
                ['name' => $category, 'source' => NewsSource::NEWS_API_ORG],
                [
                    'source' => NewsSource::NEWS_API_ORG,
                    'name' => $category,
                    'title' => $category,
                    'description' => $category,
                ]);
        }

        return $categories;
    }

}
