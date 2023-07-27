<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Services\NewsApiOrg;
use App\Services\NewsSource;
use App\Services\NewYorkTimes;
use App\Services\TheGuardian;
use Cache;

class NewsController extends Controller
{

    public function index()
    {
        //if auth user has settings
        $newsNewsApiOrg = [];
        $newsTheGuardian = [];
        $newsNewYorkTimes = [];
        //get sources keys from settings
        $sources = NewsSource::all(true);
        if (auth()->check()){
            $sources = auth()->user()->sourceSettings()->pluck('source');
            $sources = $sources->map(function ($source) {
                return $source->name;
            })->toArray();
        $authors = auth()->user()->authorSettings();

        }


        if ($sources && auth()->check()) {
            if (in_array(NewsSource::NEWS_API_ORG, $sources)) {
                $categorySettingsOfNewsApiOrg = auth()->user()->categorySettings()->whereRelation('category', 'source',
                    NewsSource::NEWS_API_ORG)->get();
                foreach ($categorySettingsOfNewsApiOrg as $categorySetting) {
                    //merge news
                    $newsNewsApiOrg = array_merge($newsNewsApiOrg,
                        NewsApiOrg::getCachedNews($categorySetting->category));
                }
            }
            if (in_array(NewsSource::THE_GUARDIAN, $sources)) {
                $categorySettingsOfTheGuardian = auth()->user()->categorySettings()->whereRelation('category', 'source',
                    NewsSource::THE_GUARDIAN)->get();
                foreach ($categorySettingsOfTheGuardian as $categorySetting) {
                    //merge news
                    $newsTheGuardian = array_merge($newsTheGuardian,
                        TheGuardian::getCachedNews($categorySetting->category));
                }
            }
            if (in_array(NewsSource::NEW_YORK_TIMES, $sources)) {
                $categorySettingsOfNewYorkTimes = auth()->user()->categorySettings()->whereRelation('category',
                    'source', NewsSource::NEW_YORK_TIMES)->get();
                foreach ($categorySettingsOfNewYorkTimes as $categorySetting) {
                    //merge news
                    $newsNewYorkTimes = array_merge($newsNewYorkTimes,
                        NewYorkTimes::getCachedNews($categorySetting->category));
                }
            }
        } else {
            $newsTheGuardian = TheGuardian::getCachedNews();
            $newsNewYorkTimes = NewYorkTimes::getCachedNews();
            $newsNewsApiOrg = NewsApiOrg::getCachedNews();
        }
        //get news by settings
        //else
        //get news by default

        $news = array_merge($newsNewsApiOrg, $newsTheGuardian, $newsNewYorkTimes);
        usort($news, function ($a, $b) {
            return strtotime($b->publishedAt) - strtotime($a->publishedAt);
        });
        return response()->json(['totalResults' => count($news), 'news' => $news]);
    }


    public function newsSources()
    {
        return response()->json(NewsSource::all());
    }
}
