<?php

namespace App\Aggregates\Merchants;

use App\Aggregates\Clients\ClientAccountAggregate;
use App\StorableEvents\Merchants\MerchantAPITokenCreated;
use App\StorableEvents\Merchants\MerchantCreated;
use App\StorableEvents\Merchants\ShopAssigned;
use Spatie\EventSourcing\AggregateRoots\AggregateRoot;

class MerchantAggregate extends AggregateRoot
{
    protected $merchant_id, $merchant_name;
    protected $client_name, $client_id;
    protected $active = false;

    protected $shops = [];

    /* MUTATORS */
    public function applyMerchantCreated(MerchantCreated $event)
    {
        $this->merchant_id = $event->getMerchant()['id'];
        $this->merchant_name = $event->getMerchant()['name'];
        $this->client_id = $event->getMerchant()['client_id'];

        $c_aggy = ClientAccountAggregate::retrieve($this->client_id);
        $this->client_name = $c_aggy->getClientName();
        $this->active = $event->getMerchant()['active'] == 1;
    }

    public function applyShopAssigned(ShopAssigned $event)
    {
        $this->shops[$event->getShop()['id']] = $event->getShop();
    }

    /* ACTIONS */
    public function createMerchant(array $merchant_array)
    {
        $this->recordThat(new MerchantCreated($merchant_array));
        return $this;
    }

    public function setNewMerchantApiToken(string $merchant_id, string $client_id)
    {
        $this->recordThat(new MerchantAPITokenCreated($merchant_id, $client_id));

        return $this;
    }

    public function addShop(array $shop)
    {
        $this->recordThat(new ShopAssigned($shop));
        return $this;
    }

    /* GETTERS */
    public function getMerchantName()
    {
        return $this->merchant_name;
    }

    public function getClientName()
    {
        return $this->client_name;
    }
}
