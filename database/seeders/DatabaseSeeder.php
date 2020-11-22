<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Database\Seeders\Shops\ShopTypesTableSeeder;
use Database\Seeders\Utility\IconSetTableSeeder;
use Database\Seeders\MenuOptions\MenuOptionsSeeder;
use Database\Seeders\PaymentProviders\PaymentProvidersTableSeeder;
use Database\Seeders\SMSProviders\SMSProvidersAndAttributesTablesSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        $this->call(MenuOptionsSeeder::class);
        $this->call(PaymentProvidersTableSeeder::class);
        $this->call(ShopTypesTableSeeder::class);
        $this->call(SMSProvidersAndAttributesTablesSeeder::class);
        $this->call(IconSetTableSeeder::class);
    }
}
