<?php

namespace App\StorableEvents\Clients;

use Spatie\EventSourcing\StoredEvents\ShouldBeStored;

class ShopOnBoarded extends ShouldBeStored
{
    protected $shop;

    public function __construct(array $shop)
    {
        $this->shop = $shop;
    }

    public function getShop()
    {
        return $this->shop;
    }
}
