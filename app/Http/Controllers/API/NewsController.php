<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Services\NewsApiOrg;
use App\Services\NewsSource;
use App\Services\NewYorkTimes;
use App\Services\TheGuardian;

class NewsController extends Controller
{

    public function index()
    {
        $newsTheGuardian = TheGuardian::getNewsByCategory();
        $newsNewYorkTimes = NewYorkTimes::getNewsByCategory();
        $newsNewsApi = NewsApiOrg::getNewsByCategory();

        $news = array_merge($newsNewsApi['data'], $newsTheGuardian['data'], $newsNewYorkTimes['data']);
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
