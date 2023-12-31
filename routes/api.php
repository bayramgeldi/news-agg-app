<?php

use App\Http\Controllers\API\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/


Route::prefix('v1')->as('api.')->namespace('API')->group(function () {

    Route::post('login', [AuthController::class,'login'])->name('login');
    Route::post('register', [AuthController::class,'register'])->name('register');
    Route::post('logout', [AuthController::class,'logout'])->name('logout')->middleware('auth:sanctum');
    Route::middleware(['auth:sanctum', 'verified'])->get('/user', function (Request $request) {
        return $request->user();
    });

    Route::get('/user/settings', [\App\Http\Controllers\API\SettingsController::class, 'index'])->middleware('auth:sanctum', 'verified');
    Route::post('/user/settings', [\App\Http\Controllers\API\SettingsController::class, 'storeSettings'])->middleware('auth:sanctum', 'verified');


    Route::get('/news', [\App\Http\Controllers\API\NewsController::class, 'index']);
    Route::get('/sources', [\App\Http\Controllers\API\NewsController::class, 'newsSources']);
    Route::get('/categories', [\App\Http\Controllers\API\MainCategoryController::class, 'index']);
    Route::get('/categories/{category}', [\App\Http\Controllers\API\MainCategoryController::class, 'getNewsByCategory']);
});


