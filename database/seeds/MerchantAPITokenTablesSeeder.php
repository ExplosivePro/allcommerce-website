<?php

use Ramsey\Uuid\Uuid;
use AllCommerce\Clients;
use Illuminate\Database\Seeder;
use AllCommerce\MerchantApiTokens;

class MerchantAPITokenTablesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $client_id = Clients::getHostClient();

        $token = MerchantApiTokens::whereClientId($client_id)
            ->whereTokenType('client')
            ->whereActive(1)
            ->first();

        if(is_null($token))
        {
            $token = MerchantApiTokens::firstOrCreate([
                'token' => Uuid::uuid4(),
                'client_id' => $client_id,
                'token_type' => 'client',
                'active' => 1
            ]);

            echo "AllCommerce Token created! {$token->id}\n";
        }
        else
        {
            echo "AllCommerce Token exists! {$token->id}\n";
        }
    }
}
