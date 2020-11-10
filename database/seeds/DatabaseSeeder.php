<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
         $this->call(ClientsTableSeeder::class);
         $this->call(RolesTableSeeder::class);
         $this->call(UsersTableSeeder::class);
         $this->call(SMSProvidersAndAttributesTablesSeeder::class);
         $this->call(MerchantAPITokenTablesSeeder::class);
         $this->call(MenuOptionsTablesSeeder::class);
         $this->call(PaymentProvidersTableSeeder::class);
         $this->call(ShopTypesTableSeeder::class);
    }
}
