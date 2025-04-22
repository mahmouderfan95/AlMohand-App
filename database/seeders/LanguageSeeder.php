<?php

namespace Database\Seeders;

use App\Models\Language\Language;
use Illuminate\Database\Seeder;

class LanguageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Language::query()->insert([
            [
                'name' => 'English',
                'code' => 'en',
                'locale' => 'en',
                'image' => 'images/en.png',
                'directory' => '',
                'sort_order' => 2,
            ],
            [
                'name' => 'Arabic',
                'code' => 'ar',
                'locale' => 'ar',
                'image' => 'images/ar.png',
                'directory' => '',
                'sort_order' => 1,
            ]
        ]);
    }
}
