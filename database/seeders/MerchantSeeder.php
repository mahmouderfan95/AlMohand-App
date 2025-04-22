<?php

namespace Database\Seeders;

use App\Models\Merchant\Merchant;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class MerchantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (App::environment('local')) {
            $merchant = [
                'id' => Str::uuid(),
                'name' => 'Ahmed Osama',
                'phone' => '201100960900',
                'password' => Hash::make('Moh@nn@d123'),
                'email' => 'merchant@almohannad.com',
                'verified_at' => now()
            ];

            Merchant::query()->create($merchant);
        }
    }
}
