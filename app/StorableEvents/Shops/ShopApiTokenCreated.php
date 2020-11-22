<?php

namespace App\StorableEvents\Shops;

use Spatie\EventSourcing\StoredEvents\ShouldBeStored;

class ShopApiTokenCreated extends ShouldBeStored
{
    protected $shop, $client;

    public function __construct(string $shop, string $client)
    {
        $this->shop = $shop;
        $this->client = $client;
    }

    public function getShop()
    {
        return $this->shop;
    }

    public function getClient()
    {
        return $this->client;
    }
}
