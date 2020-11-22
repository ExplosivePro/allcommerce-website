<?php

namespace Database\Seeders\Shops;

use App\Models\Shops\ShopTypes;
use Illuminate\Database\Seeder;

class ShopTypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ShopTypes::firstOrCreate(['name' => 'Web Only']);
        ShopTypes::firstOrCreate(['name' => 'Shopify']);
    }
}
