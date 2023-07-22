<?php

namespace App\Services;

use App\Models\Author;
use App\Models\Category;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Str;

class TheGuardian extends NewsSource
{

    public static function getNewsByCategory(Category $category = null): array
    {
        $news = [];
        $query = [
            'api-key' => config('services.TheGuardian.key'),
            'from-date' => Carbon::now()->subDays(1)->format('Y-m-d'),
            'to-date' => Carbon::now(),
            'show-fields' => 'thumbnail,publication,byline',
        ];
        //add section key to query if category is not null
        if ($category) {
            $query['section'] = $category->name;
        }
        $response = Http::get(config('services.TheGuardian.base_url').'/search', $query);
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

            if (!$category) {
                $category = Category::where('source', self::THE_GUARDIAN)->where('name',
                    $article['sectionId'])->first();
            }
            if (!$category) {
                continue;
            }

            //add author if it exists
            if (isset($article['fields']['byline'])) {
                $author = Author::firstOrCreate(
                    ['name' => Str::slug($article['fields']['byline']), 'source' => self::THE_GUARDIAN],
                    [
                        'source' => self::THE_GUARDIAN,
                        'name' => Str::slug($article['fields']['byline']),
                        'title' => $article['fields']['byline'],
                        'description' => $article['fields']['byline'],
                    ]
                );
            }
            $articleClass = new Article(
                self::THE_GUARDIAN,
                (array) $subSource,
                (string) isset($article['fields']['byline']) ? $article['fields']['byline'] : self::THE_GUARDIAN,
                (string) $article['webTitle'],
                $category,
                (string) isset($article['fields']['trailText']) ? $article['fields']['trailText'] : '',
                (string) $article['webUrl'],
                (string) $article['fields']['thumbnail'],
                (string) Carbon::parse($article['webPublicationDate'])->format('Y-m-d H:i:s'),
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
                [
                    'source' => NewsSource::THE_GUARDIAN,
                    'name' => $category['id'],
                    'title' => $category['webTitle'],
                    'description' => $category['webTitle'],
                ]);
        }
        return $categories;
    }

}
