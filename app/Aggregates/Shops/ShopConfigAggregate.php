<?php

namespace AllCommerce\Aggregates\Shops;

use AllCommerce\Clients;
use AllCommerce\Events\Clients\NewClientAPITokenSet;
use AllCommerce\Events\Shops\NewShopAPITokenSet;
use AllCommerce\Events\Shops\NewShopCreated;
use AllCommerce\Events\Shops\ShopAssignedPaymentProviderSet;
use AllCommerce\MerchantApiTokens;
use AllCommerce\Merchants;
use AllCommerce\Models\PaymentGateways\ShopAssignedPaymentProviders;
use AllCommerce\Shops;
use Spatie\EventSourcing\AggregateRoots\AggregateRoot;

class ShopConfigAggregate extends AggregateRoot
{
    protected $shop_id, $merchant_id, $client_id, $shop_name, $active, $date_created, $last_updated;
    protected $shop_url, $shop_type;
    protected $deleted = false;

    protected $shop_assigned_payment_providers = [];

    protected $oauth_api_token;

    /* PREVIOUSLY APPLIED DATA */
    public function applyNewShopCreated(NewShopCreated $event)
    {
        $shop = Shops::find($event->getShop()['id']);

        $this->shop_id = $shop->id;
        $this->merchant_id = $shop->merchant_id;
        $this->client_id = $shop->client_id;
        $this->shop_name = $shop->shop_name;
        $this->shop_url = $shop->shop_url;
        $this->active = $shop->active;
        $this->date_created = $shop->created_at;
        $this->last_updated = $shop->updated_at;
        $this->shop_type = $shop->shoptype()->first()->name;
    }

    public function applyNewShopAPITokenSet(NewShopAPITokenSet $event)
    {
        $this->oauth_api_token = $event->getToken();
    }

    public function applyShopAssignedPaymentProviderSet(ShopAssignedPaymentProviderSet $event)
    {
        if(array_key_exists($event->getAssigned()['id'], $this->shop_assigned_payment_providers))
        {
            $this->shop_assigned_payment_providers[$event->getAssigned()['id']] = $event->getAssigned();
        }
    }

    public function apply()
    {

    }

    /* SETTERS */
    public function setShop(Shops $shop)
    {
        $this->shop_id = $shop->id;
        $this->merchant_id = $shop->merchant_id;
        $this->client_id = $shop->client_id;
        $this->shop_name = $shop->shop_name;
        $this->shop_url = $shop->shop_url;
        $this->active = $shop->active;
        $this->date_created = $shop->created_at;
        $this->last_updated = $shop->updated_at;
        $this->shop_type = $shop->shoptype()->first()->name;

        $this->recordThat(new NewShopCreated($shop->toArray()));

        return $this;
    }

    public function setNewShopApiToken(MerchantApiTokens $token)
    {
        $this->oauth_api_token = $token->toArray();

        $this->recordThat(new NewShopAPITokenSet($token->toArray(), $this->shop_id));

        return $this;
    }

    public function setAssignedPaymentProvider(ShopAssignedPaymentProviders $record)
    {
        $this->recordThat(new ShopAssignedPaymentProviderSet($record->toArray()));

        return $this;
    }

    /* MUTATORS */
    public function updateShop(Shops $shop)
    {
        return $this;
    }

    /* GETTERS */
    public function getShop()
    {
        $results = false;

        if(!is_null($this->shop_id))
        {
            $results = Shops::find($this->client_id);
        }

        return $results;
    }

    public function getClient()
    {
        $results = false;

        if(!is_null($this->client_id))
        {
            $results = Clients::find($this->client_id);
        }

        return $results;
    }

    public function getMerchant()
    {
        $results = false;

        if(!is_null($this->merchant_id))
        {
            $results = Merchants::find($this->merchant_id);
        }

        return $results;
    }
}
