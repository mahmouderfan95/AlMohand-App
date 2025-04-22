<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GeoLocationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::beginTransaction();

        DB::table('zone_translations')->delete();
        DB::table('zone')->delete();
        DB::table('cities')->delete();
        DB::table('countries')->delete();

        $languages = DB::table('languages')->pluck('id', 'code')->toArray(); // Example: ['en' => 1, 'ar' => 2]

        $country_id = DB::table('countries')->insertGetId([
            'code' => 'SA',
            'flag' => 'images/sa_flag.png',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('country_translations')->insert([
            [
                'country_id' => $country_id,
                'language_id' => $languages['en'],
                'name' => 'Saudi Arabia',
            ],
            [
                'country_id' => $country_id,
                'language_id' => $languages['ar'],
                'name' => 'المملكة العربية السعودية',
            ],
        ]);

        $city_names = [
            ['en' => 'Riyadh', 'ar' => 'الرياض'],
            ['en' => 'Jeddah', 'ar' => 'جدة'],
            ['en' => 'Dammam', 'ar' => 'الدمام'],
            ['en' => 'Makkah', 'ar' => 'مكة المكرمة'],
            ['en' => 'Medina', 'ar' => 'المدينة المنورة'],
            ['en' => 'Abha', 'ar' => 'أبها'],
            ['en' => 'Tabuk', 'ar' => 'تبوك'],
            ['en' => 'Buraidah', 'ar' => 'بريدة'],
            ['en' => 'Al Khobar', 'ar' => 'الخبر'],
            ['en' => 'Najran', 'ar' => 'نجران'],
        ];

        foreach ($city_names as $key => $city) {
            $city_id = DB::table('cities')->insertGetId([
                'region_id'  => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::table('city_translations')->insert([
                [
                    'city_id'     => $city_id,
                    'language_id' => $languages['en'],
                    'name'        => $city['en'],
                    'created_at'  => now(),
                    'updated_at'  => now(),
                ],
                [
                    'city_id'     => $city_id,
                    'language_id' => $languages['ar'],
                    'name'        => $city['ar'],
                    'created_at'  => now(),
                    'updated_at'  => now(),
                ],
            ]);
        }

        $zone_names = [
            ['en' => 'Zone 1', 'ar' => 'المنطقة 1'],
            ['en' => 'Zone 2', 'ar' => 'المنطقة 2'],
            ['en' => 'Zone 3', 'ar' => 'المنطقة 3'],
            ['en' => 'Zone 4', 'ar' => 'المنطقة 4'],
            ['en' => 'Zone 5', 'ar' => 'المنطقة 5'],
            ['en' => 'Zone 6', 'ar' => 'المنطقة 6'],
            ['en' => 'Zone 7', 'ar' => 'المنطقة 7'],
            ['en' => 'Zone 8', 'ar' => 'المنطقة 8'],
            ['en' => 'Zone 9', 'ar' => 'المنطقة 9'],
            ['en' => 'Zone 10', 'ar' => 'المنطقة 10'],
        ];

        foreach ($zone_names as $zone) {
            $zone_id = DB::table('zone')->insertGetId([
                'city_id' => $city_id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::table('zone_translations')->insert([
                [
                    'zone_id' => $zone_id,
                    'language_id' => $languages['en'],
                    'name' => $zone['en'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'zone_id' => $zone_id,
                    'language_id' => $languages['ar'],
                    'name' => $zone['ar'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
            ]);
        }

        DB::commit();
    }
}
