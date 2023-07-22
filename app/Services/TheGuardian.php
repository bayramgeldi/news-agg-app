<?php

namespace App\Services;

use App\Models\Category;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;

class TheGuardian extends NewsSource
{

    public static function getNews()
    {
        $news = [];
        $response = Http::get(config('services.TheGuardian.base_url').'/search', [
            'api-key' => config('services.TheGuardian.key'),
            'from-date' => Carbon::now()->subDays(1)->format('Y-m-d'),
            'to-date' => Carbon::now(),
            'show-fields' => 'thumbnail,publication',
            'show-references' => 'author',
        ]);
        if ($response->failed()) {
            return $news;
        }
        $data = json_decode($response->getBody()->getContents(), true);
        $news['totalResults'] = $data['response']['total'];
        foreach ($data['response']['results'] as $article) {
            $subSource = [
                'id' => NewsSource::THE_GUARDIAN,
                'name' => "The Guardian",
            ];
            $category = Category::where('source', self::THE_GUARDIAN)->where('name', $article['sectionId'])->first();
            if (!$category) {
                continue;
            }
            $articleClass = new Article(
                self::THE_GUARDIAN,
                (array) $subSource,
                (string) isset($article['references']['author']) ? $article['references']['author'] : '',
                (string) $article['webTitle'],
                $category,
                (string) isset($article['fields']['trailText']) ? $article['fields']['trailText'] : '',
                (string) $article['webUrl'],
                (string) $article['fields']['thumbnail'],
                (string) $article['webPublicationDate'],
                (string) ''
            );
            $news['data'][] = $articleClass->collect();

        }
        return $news;

    }


    public static function getCategories(): array
    {
        $categories = [];
        $response = Http::get(config('services.TheGuardian.base_url').'/sections', [
            'api-key' => config('services.TheGuardian.key'),
            'from-date' => Carbon::now()->subDays(1)->format('Y-m-d'),
            'to-date' => Carbon::now(),
            'show-fields' => 'thumbnail,publication',
            'show-references' => 'author',
        ]);
        if ($response->failed()) {
            return $categories;
        }
        $data = json_decode($response->getBody()->getContents(), true);

        foreach ($data['response']['results'] as $category) {
            $categories[] = [
                'source' => NewsSource::THE_GUARDIAN,
                'name' => $category['id'],
                'title' => $category['webTitle'],
                'description' => $category['webTitle'],
            ];
            Category::updateOrInsert(
                ['name' => $category['id'], 'source' => NewsSource::THE_GUARDIAN],
                ['source' => NewsSource::THE_GUARDIAN,
                'name' => $category['id'],
                'title' => $category['webTitle'],
                'description' => $category['webTitle'],
            ]);
        }
        return $categories;
    }

}
