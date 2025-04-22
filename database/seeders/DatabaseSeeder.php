<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Database\Seeders\Categoryseeder;
use App\Models\Vendor;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {

        DB::beginTransaction();

        $this->call(LanguageSeeder::class);

         //\App\Models\User::factory(10)->create();
          $this->call(Categoryseeder::class);


        $this->call(SuperAdminRoleSeeder::class);
        $this->call(RoleAndPermissionSeeder::class);

        $this->call(OneCardSettingSeeder::class);
        $this->call(MsegatSettingSeeder::class);
        $this->call(SmsMisrSettingSeeder::class);
        $this->call(UrpayWalletSettingSeeder::class);
        $this->call(SettingDetailsSeeder::class);
        $this->call(StaticPageSeeder::class);
        $this->call(GeoLocationSeeder::class);

        DB::commit();
    }
}
