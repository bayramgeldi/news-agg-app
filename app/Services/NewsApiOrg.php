<?php

namespace App\Services;

use App\Models\Category;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;

class NewsApiOrg extends NewsSource
{
    public static function getNews(): array
    {
        $news = [];
        $response = Http::get(config('services.NewsAPIOrg.base_url').'/top-headlines', [
            'country' => 'us',
            'apiKey' => config('services.NewsAPIOrg.key'),
        ]);

        if ($response->failed()) {
            return $news;
        }
        $data = json_decode($response->getBody()->getContents(), true);
        $news['totalResults'] = $data['totalResults'];
        foreach ($data['articles'] as $article) {
            $subSource = $article['source'];
            $category = Category::where('source', self::NEWS_API_ORG)->where('name', 'general')->first();
            $articleClass = new Article(
                self::NEWS_API_ORG,
                (array) $subSource,
                (string) $article['author'],
                (string) $article['title'],
                $category,
                (string) $article['description'],
                (string) $article['url'],
                (string) $article['urlToImage'],
                (string) $article['publishedAt'],
                (string) $article['content']
            );
            $news['data'][] = $articleClass->collect();
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
