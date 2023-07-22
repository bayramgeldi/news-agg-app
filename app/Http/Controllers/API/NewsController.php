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
        $newsTheGuardian = TheGuardian::getNews();
        $newsNewYorkTimes = NewYorkTimes::getNews();
        $newsNewsApi = NewsApiOrg::getNews();

        $news = array_merge($newsNewsApi['data'], $newsTheGuardian['data'], $newsNewYorkTimes['data']);
        //$news = TheGuardian::getCategories();
        //$news = NewsApiOrg::getCategories();
        //$news = NewYorkTimes::getCategories();
        return response()->json(['news' => $news]);
    }


    public function newsSources()
    {
        return response()->json(NewsSource::all());
    }
}
