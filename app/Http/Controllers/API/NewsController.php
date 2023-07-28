<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Category;
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
        if (auth('sanctum')->check()) {
            $sources = auth('sanctum')->user()->sourceSettings()->pluck('source');
            $sources = $sources->map(function ($source) {
                return $source->name;
            })->toArray();
            $authors = auth('sanctum')->user()->authorSettings();

        }

        if ($sources && auth('sanctum')->check()) {
            if (in_array(NewsSource::NEWS_API_ORG, $sources)) {
                $categorySettingsOfNewsApiOrg = $this->categories(NewsSource::NEWS_API_ORG);
                foreach ($categorySettingsOfNewsApiOrg as $categorySetting) {
                    //merge news
                    $newsNewsApiOrg = array_merge($newsNewsApiOrg,
                        NewsApiOrg::getCachedNews($categorySetting));
                }
            }
            if (in_array(NewsSource::THE_GUARDIAN, $sources)) {
                $categorySettingsOfTheGuardian = $this->categories(NewsSource::THE_GUARDIAN);
                foreach ($categorySettingsOfTheGuardian as $categorySetting) {
                    //merge news
                    $newsTheGuardian = array_merge($newsTheGuardian,
                        TheGuardian::getCachedNews($categorySetting));
                }
            }
            if (in_array(NewsSource::NEW_YORK_TIMES, $sources)) {
                $categorySettingsOfNewYorkTimes = $this->categories(NewsSource::NEW_YORK_TIMES);
                foreach ($categorySettingsOfNewYorkTimes as $categorySetting) {
                    //merge news
                    $newsNewYorkTimes = array_merge($newsNewYorkTimes,
                        NewYorkTimes::getCachedNews($categorySetting));
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

    /**
     * @return \App\Models\UserCategorySetting[]|\Illuminate\Database\Eloquent\Collection|\LaravelIdea\Helper\App\Models\_IH_UserCategorySetting_C
     */
    private function categories($source
    ): array|\LaravelIdea\Helper\App\Models\_IH_UserCategorySetting_C|\Illuminate\Database\Eloquent\Collection
    {
        $ids=[];
        $settings=auth('sanctum')->user()->categorySettings()->with('category.sub_categories')->get();
       $ids =  $settings->map(function ($setting) {
            return $setting->category->sub_categories->map(function ($category) use (&$ids) {
                return $category->id;
            });
        });
        return Category::where('source', $source)->whereIn('id',$ids[0])->get();
    }
}
