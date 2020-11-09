<?php

namespace AllCommerce\Aggregates\Merchants;

use AllCommerce\Clients;
use AllCommerce\Events\Clients\NewClientAPITokenSet;
use AllCommerce\Events\Merchants\NewShopAPITokenSet;
use AllCommerce\Events\Merchants\NewShopIdentified;
use AllCommerce\Events\Merchants\MerchantDeleted;
use AllCommerce\Events\Merchants\MerchantUpdated;
use AllCommerce\Events\Merchants\NewMerchantAPITokenSet;
use AllCommerce\Events\Merchants\SetNewMerchant;
use AllCommerce\MerchantApiTokens;
use AllCommerce\Merchants;
use AllCommerce\Shops;
use Spatie\EventSourcing\AggregateRoots\AggregateRoot;

class MerchantConfigAggregate extends AggregateRoot
{
    protected $merchant_id, $client_id, $merchant_name, $active, $date_created, $last_updated;
    protected $deleted = false;

    protected $oauth_api_tokens = [
        'merchant' => null,
        'shops' => []
    ];

    protected $shops = [];

    /* PREVIOUSLY APPLIED DATA */
    public function applySetNewMerchant(SetNewMerchant $event)
    {
        $this->merchant_id = $event->getMerchant()->id;
        $this->client_id = $event->getMerchant()->client_id;
        $this->merchant_name = $event->getMerchant()->name;
        $this->active = $event->getMerchant()->active;
        $this->date_created = $event->getMerchant()->created_at;
        $this->last_updated = $event->getMerchant()->updated_at;
    }

    public function applyMerchantUpdated(MerchantUpdated $event)
    {
        $this->merchant_name = $event->getMerchant()['name'];
        $this->active = $event->getMerchant()['active'];
        $this->last_updated = $event->getMerchant()['updated_at'];
    }

    public function applyNewMerchantAPITokenSet(NewMerchantAPITokenSet $event)
    {
        $this->oauth_api_tokens['merchant'] = $event->getToken();
    }

    public function applyMerchantDeleted(MerchantDeleted $event)
    {
        $this->deleted = true;
    }

    public function applyNewShopIdentified(NewShopIdentified $event)
    {
        $this->shops[$event->getShop()->id] = $event->getShop();
    }

    public function applyNewShopAPITokenSet(NewShopAPITokenSet $event)
    {
        $this->oauth_api_tokens['shops'][$event->getId()] = $event->getToken();
    }

    public function apply()
    {

    }

    /* SETTERS */
    public function setMerchant(Merchants $merchant)
    {
        $this->merchant_id = $merchant->id;
        $this->client_id = $merchant->client_id;
        $this->merchant_name = $merchant->name;
        $this->active = $merchant->active;
        $this->date_created = $merchant->created_at;
        $this->last_updated = $merchant->updated_at;

        $this->recordThat(new SetNewMerchant($merchant));

        return $this;
    }

    public function setNewMerchantApiToken(MerchantApiTokens $token)
    {
        $this->oauth_api_tokens['merchant'] = $token->toArray();

        $this->recordThat(new NewMerchantAPITokenSet($token->toArray(), $this->merchant_id));

        return $this;
    }

    public function deleteMerchant($id)
    {
        $this->recordThat(new MerchantDeleted($id));

        return $this;
    }

    public function setNewShop(Shops $shop)
    {
        $this->shops[$shop->id] = $shop;
        $this->recordThat(new NewShopIdentified($shop));

        return $this;
    }

    public function setNewShopApiToken(MerchantApiTokens $token, string $shop_id)
    {
        $this->oauth_api_tokens['shops'][$shop_id] = $token->toArray();

        $this->recordThat(new NewShopAPITokenSet($token->toArray(), $shop_id));

        return $this;
    }

    /* MUTATORS */
    public function updateMerchant(Merchants $merchant)
    {
        $this->merchant_name = $merchant->name;
        $this->active = $merchant->active;
        $this->last_updated = $merchant->updated_at;

        $this->recordThat(new MerchantUpdated($merchant->toArray()));

        return $this;
    }

    /* GETTERS */
    public function getMerchant()
    {
        $results = false;

        if(!is_null($this->merchant_id))
        {
            $results = Merchants::find($this->merchant_id);
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

    public function getMerchantApiToken()
    {
        $results = false;

        if(!is_null($this->oauth_api_tokens['merchant']))
        {
            $results = MerchantApiTokens::find($this->oauth_api_tokens['merchant']);
        }

        return $results;
    }
}
