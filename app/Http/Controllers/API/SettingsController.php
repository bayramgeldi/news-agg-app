<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSettingsRequest;
use Dflydev\DotAccessData\Data;

class SettingsController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $userCategorySettings = $user->categorySettings()->get();
        $userAuthorSettings = $user->authorSettings()->get();
        $userSourceSettings = $user->sourceSettings()->get();

        return response()->json([
            'userCategorySettings' => $userCategorySettings,
            'userAuthorSettings' => $userAuthorSettings,
            'userSourceSettings' => $userSourceSettings,
        ]);
    }

    public function storeSettings(StoreSettingsRequest $request){
        $user = auth()->user();
        $user->categorySettings()->delete();
        $user->authorSettings()->delete();
        $user->sourceSettings()->delete();

        foreach (request('sources') as $source) {
            $user->sourceSettings()->create([
                'source' => $source,
            ]);
        }
        foreach (request('categories') as $category) {
            $user->categorySettings()->create([
                'category_id' => $category,
            ]);
        }
        foreach (request('authors') as $author) {
            $user->authorSettings()->create([
                'author_id' => $author,
            ]);
        }

        return response()->json([
            'message' => 'Settings saved successfully',
            'data' => [
                'userCategorySettings' => $user->categorySettings()->with('category')->get(),
                'userAuthorSettings' => $user->authorSettings()->with('author')->get(),
                'userSourceSettings' => $user->sourceSettings()->get(),
            ]
        ]);
    }
}
