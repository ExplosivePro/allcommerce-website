<?php

namespace App\Projectors\API\Tokens;

use App\Models\API\MerchantApiToken;
use App\StorableEvents\Clients\ClientAPITokenCreated;
use App\StorableEvents\Merchants\MerchantAPITokenCreated;
use App\StorableEvents\Shops\ShopApiTokenCreated;
use Ramsey\Uuid\Uuid;
use Spatie\EventSourcing\EventHandlers\Projectors\Projector;

class APITokenProjector extends Projector
{
    public function onClientAPITokenCreated(ClientAPITokenCreated $event)
    {
        $token = Uuid::uuid4()->toString();
        $model = MerchantApiToken::firstOrCreate([
            'token' => $token,
            'client_id' => $event->getClient(),
            'token_type' => 'client'
        ]);

        $model->scopes = [];
        $model->save();
    }

    public function onMerchantAPITokenCreated(MerchantAPITokenCreated $event)
    {
        $token = Uuid::uuid4()->toString();

        $model = MerchantApiToken::firstOrCreate([
            'token' => $token,
            'client_id' => $event->getClient(),
            'token_type' => 'merchant'
        ]);

        $model->scopes = ['merchant_id' => $event->getMerchant()];
        $model->save();
    }

    public function onShopApiTokenCreated(ShopApiTokenCreated $event)
    {
        $token = Uuid::uuid4()->toString();

        $model = MerchantApiToken::firstOrCreate([
            'token' => $token,
            'client_id' => $event->getClient(),
            'token_type' => 'shop'
        ]);

        $model->scopes = ['shop_id' => $event->getShop()];
        $model->save();
    }
}
