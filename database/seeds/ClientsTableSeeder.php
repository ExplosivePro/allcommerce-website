<?php

use AllCommerce\Clients;
use Illuminate\Database\Seeder;

class ClientsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        echo "Creating AllCommerce the Host Client\n";
        $ac = Clients::firstOrCreate([
            'name' => 'AllCommerce',
            'active' => 1
        ]);
        echo ' AllCommerce UUID - '.$ac->id."\n";
    }
}
