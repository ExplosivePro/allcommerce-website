<?php

namespace App\Projectors\Shops;

use App\Aggregates\Shops\ShopConfigAggregate;
use App\Models\Shopify\ShopifyInstalls;
use App\Models\Shops\Shop;
use App\StorableEvents\Shops\Shopify\NonceCreated;
use Spatie\EventSourcing\EventHandlers\Projectors\Projector;

class ShopifyActivityProjector extends Projector
{
    public function onNonceCreated(NonceCreated $event)
    {
        $aggy = ShopConfigAggregate::retrieve($event->getShop());
        $nonce = $event->getNonce();
        $shop_url = $aggy->getShopUrl();

        // Using the Shop ID, get the remaining variables needed to make the record.
        $payload = [
            'nonce' => $nonce,
            'shopify_store_url' => $shop_url,
            'shop_uuid' => $event->getShop(),
            'merchant_id' => $aggy->getMerchantId(),
            'client_id' => $aggy->getClientId()
        ];

        // Insert a record into the Shopify Installs table.
        $install_record = ShopifyInstalls::firstOrCreate($payload);

        //Call Aggy the ShopConfigAggregate and set the shop_install_id
        $aggy->continueShopifyInstall($install_record)->persist();
    }
}
