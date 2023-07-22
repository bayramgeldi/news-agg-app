<?php

namespace App\Services;

use App\Models\Category;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;

class NewYorkTimes extends NewsSource
{

    public static function getNews(){
        $news = [];
        $response = Http::get(config('services.NewYorkTimes.base_url').'/topstories/v2/home.json', [
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
            $category = Category::where('source', self::NEW_YORK_TIMES)->where('name', $article['section'])->first();
            if (!$category){
                continue;
            }
            $articleClass = new Article(
                self::NEW_YORK_TIMES,
                (array) $subSource,
                (string) isset($article['byline']) ? $article['byline'] : '',
                (string) $article['title'],
                $category,
                (string) isset($article['abstract']) ? $article['abstract'] : '',
                (string) $article['url'],
                (string) $article['multimedia'][1]['url'],
                (string) $article['published_date'],
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
            $cat = [
                'source' => NewsSource::NEW_YORK_TIMES,
                'name' => $category['section'],
                'title' => $category['display_name'],
                'description' => $category['display_name'],
            ];
            $categories[] = $cat;
            Category::updateOrInsert(
                ['name' => $cat['name'], 'source' => $cat['source']],
                ['source' => $cat['source'],
                    'name' => $cat['name'],
                    'title' => $cat['title'],
                    'description' => $cat['description'],
                ]);
        }
        return $categories;
    }

}
