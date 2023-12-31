<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\MainCategory;
use App\Services\NewsApiOrg;
use App\Services\NewsSource;
use App\Services\NewYorkTimes;
use App\Services\TheGuardian;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        foreach (NewsSource::$categories as $category) {
            MainCategory::firstOrCreate([
                'name' => $category,
            ],[
                'name' => $category,
                'title' => $category,
                'description' => $category,
            ]);
        }


        TheGuardian::getCategories();
        NewYorkTimes::getCategories();
        NewsApiOrg::getCategories();
    }
}
