<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\MainCategory;
use App\Services\NewsApiOrg;
use App\Services\NewsSource;
use App\Services\NewYorkTimes;
use App\Services\TheGuardian;
use Cache;
use Illuminate\Http\Request;

class MainCategoryController extends Controller
{
    public function index()
    {
        return MainCategory::with('sub_categories')->get();
    }

    public function getNewsByCategory(string $category)
    {
        if (!MainCategory::where('name', $category)->exists()) {
            return response()->json([
                'message' => 'Category not found'
            ], 404);
        }
        $mainCategory = MainCategory::where('name', $category)->with('sub_categories')->first();

        $news = [];
        $mainCategory->sub_categories->map(function ($category) use (&$news) {

            $newsNewsApiOrg = NewsApiOrg::getCachedNews($category);

            $newsTheGuardian = TheGuardian::getCachedNews($category);

            $newsNewYorkTimes = NewYorkTimes::getCachedNews($category);

            $news = array_merge($news, $newsNewsApiOrg, $newsTheGuardian, $newsNewYorkTimes);
        });

        usort($news, function ($a, $b) {
            return strtotime($b->publishedAt) - strtotime($a->publishedAt);
        });

        return response()->json(['totalResults' => count($news), 'news' => $news]);
    }

}
