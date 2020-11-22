<?php

namespace App\StorableEvents\Shops;

use Spatie\EventSourcing\StoredEvents\ShouldBeStored;

class ShopCreated extends ShouldBeStored
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
